<?php

namespace App\Http\Controllers\Companies;

use App\Client;
use App\Company;
use App\Helpers\BootForm;
use App\Http\Controllers\BaseController;
use App\Http\Traits\SoftDeleteTrait;
use App\Invoice;
use DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\DataTables\DataTables;

class InvoicesController extends BaseController
{
    use SoftDeleteTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->all()) {
            $company_id = auth('admin')->user()->adminable->id;
            $children = Client::where('company_id', $company_id)
                ->whereNull('deleted_at')->get()->pluck('id');
            $children_str = $children->toArray();
            $invoices = Invoice::with('client');
            if ($request->input('client_id') != '') {
                $invoices->where('client_id', $request->input('client_id'))
                    ->whereNull('deleted_at');
            } else {
                $invoices->whereIn('client_id', $children_str)
                    ->whereNull('deleted_at');
            }
            if ($request->input('code') != '') {
                $invoices->where('code', $request->input('code'));
            }
            if ($request->input('from') != '') {
                $invoices->where(DB::raw('concat(deducted_year ,"-0",deducted_month)'), '>=', date('Y-m', strtotime($request->input('from'))));
            }
            if ($request->input('to') != '') {
                $invoices->where(DB::raw('concat(deducted_year ,"-0",deducted_month)'), '<=', date('Y-m', strtotime($request->input('to'))));

            }
            $invoices->get();

            return Datatables::of($invoices)
                ->editColumn('code', function ($model) {
                    return BootForm::routeLink('invoices.show', $model->id, ['value' => $model->code]);

                })->editColumn('tax', function ($model) {
                    return $model->tax . ' %';

                })->editColumn('created_at', function ($model) {
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
                    return BootForm::linkOfEdit('invoices.edit', $model->id, $model->name)
                        . BootForm::linkOfDelete('invoices.soft_delete', $model->id, $model->name, 'link', true);
//                        . BootForm::linkOfPermanentDelete('payments.destroy', $model->id, $model->name, 'link', true);

                })
                ->rawColumns(['code', 'action'])
                ->make(true);
        }
        $clients = Client::where('company_id', auth('admin')->user()->adminable->id)
            ->whereNull('deleted_at')->get()->pluck('name', 'id');
        return view('companies.invoices.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        $to_corporate = Company::findOrFail(auth('admin')->user()->adminable->id);
        return view('companies.invoices.show', compact('to_corporate', 'invoice'));
    }

    /**
     * @param $id
     * @return mixed
     */
    public function downloadPDF($id)
    {
        $invoice = Invoice::findOrFail($id);
        $to_corporate = Company::findOrFail(auth('admin')->user()->adminable->id);
        $pdf = PDF::loadView('invoice.pdf', compact('invoice', 'to_corporate'));
        return $pdf->download('invoice.pdf');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('companies.invoices.edit', compact('invoice'));

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
        $invoice = Invoice::findOrFail($id);
        $inputs = $request->all();
        $inputs['deducted_year'] = date('y', strtotime($request->deducted_date));
        $inputs['deducted_month'] = date('m', strtotime($request->deducted_date));
        $invoice->update($inputs);
        flash()->success('Data was saved successfully');
        return redirect(route('invoices.show', $invoice->id));
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
        $invoice = Invoice::findOrFail($id);
        if ($this->softDeleteModel($invoice)) {
            $invoice->save();
            flash()->success('Data was deleted successfully');
        } else {
            flash()->error('failed to delete data, please try again later');
        }
        return redirect()->route('invoices.index');
    }
}
