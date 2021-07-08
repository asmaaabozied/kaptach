<?php

namespace App\Http\Controllers\Companies;

use App\Client;
use App\Company;
use App\Helpers\BootForm;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Traits\SoftDeleteTrait;
use App\Payment;
use DateTime;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PaymentsController extends BaseController
{
    use SoftDeleteTrait;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->all()) {
            $company_id = auth('admin')->user()->adminable->id;
            $children = Client::where('company_id', $company_id)
                ->whereNull('deleted_at')->get()->pluck('id');
            $children_str = $children->toArray();
            $payments = Payment::with('client');
            if ($request->input('client_id') != '') {
                $payments->where('client_id', $request->input('client_id'))
                    ->whereNull('deleted_at');
            } else {
                $payments->whereIn('client_id', $children_str)->whereNull('deleted_at');
            }

            if ($request->input('from') != '') {
                $payments->where(DB::raw('concat(deducted_year ,"-0",deducted_month)'), '>=', date('Y-m', strtotime($request->input('from'))));
            }
            if ($request->input('to') != '') {
                $payments->where(DB::raw('concat(deducted_year ,"-0",deducted_month)'), '<=', date('Y-m', strtotime($request->input('to'))));

            }
            $payments->get();

            return Datatables::of($payments)
                ->editColumn('created_at', function ($model) {
                    return date('Y-m-d', strtotime($model->created_at));
                })
                ->editColumn('client.name', function ($model) {
                    return $model->client['name'];
                })->editColumn('deducted_month', function ($model) {
                    $dateObj = DateTime::createFromFormat('!m', $model->deducted_month);
                    $monthName = $dateObj->format('F');
                    return $monthName;
                })
                ->addColumn('action', function ($model) use ($request) {
                    return BootForm::linkOfEdit('payments.edit', $model->id, $model->name)
                        . BootForm::linkOfDelete('payments.soft_delete', $model->id, $model->name, 'link', true);
//                        . BootForm::linkOfPermanentDelete('payments.destroy', $model->id, $model->name, 'link', true);

                })
                ->make(true);
        }
        $clients = Client::where('company_id', auth('admin')->user()->adminable->id)
            ->whereNull('deleted_at')->get()->pluck('name', 'id');
        return view('companies.payments.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $clients = Client::where('company_id', auth('admin')->user()->adminable->id)
            ->whereNull('deleted_at')->get()->pluck('name', 'id');
        return view('companies.payments.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $inputs = $request->all();
        $inputs['deducted_year'] = date('y', strtotime($request->deducted_date));
        $inputs['deducted_month'] = date('m', strtotime($request->deducted_date));
        Payment::create($inputs);
        flash()->success('Data was saved successfully');
        return redirect(route('payments.index'));

    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $clients = Client::where('company_id', auth('admin')->user()->adminable->id)
            ->whereNull('deleted_at')->get()->pluck('name', 'id');
        $payment = Payment::with('client')
            ->whereNull('deleted_at')->findOrFail($id);
        return view('companies.payments.edit', compact('payment', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $payment = Payment::whereNull('deleted_at')->findOrFail($id);
        $inputs = $request->all();
        $inputs['deducted_year'] = date('y', strtotime($request->deducted_date));
        $inputs['deducted_month'] = date('m', strtotime($request->deducted_date));
        $payment->update($inputs);
        flash()->success('Data was saved successfully');
        return redirect(route('payments.index'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function softDelete($id)
    {
        $payment = Payment::findOrFail($id);
        if ($this->softDeleteModel($payment)) {
            $payment->save();
            flash()->success('Data was deleted successfully');
        } else {
            flash()->error('failed to delete data, please try again later');
        }
        return redirect()->route('payments.index');
    }
}
