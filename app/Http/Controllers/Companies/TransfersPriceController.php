<?php

namespace App\Http\Controllers\Companies;

use App\Airport;
use App\Car_model;
use App\Client;
use App\Company;
use App\Helpers\BootForm;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Traits\SoftDeleteTrait;
use App\Transfer_price_list;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TransfersPriceController extends BaseController
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

            $transfer_prices = Transfer_price_list::with('clients', 'airport', 'carModel')
                ->where('company_id', auth('admin')->user()->adminable->id)
                ->whereNull('deleted_at')->get();

            return Datatables::of($transfer_prices)
                ->editColumn('carModel.model_name', function ($model) {
                    $text = '';
                    $text .= $model->carModel['model_name'];
                    if ($model->carModel['image'])
                        $text .= "<a href='" . $model->carModel['image']['original'] . "' title='" . $model->carModel['model_name'] . "' class='cbox'><img src='" . $model->carModel['image']['thumb'] . "' class='img-thumbnail img-responsive' id='img-preview'></a>";
                    else
                        $text .= "<img src='" . url('assets/img/no-image-available.jpg') . "' class='img-thumbnail img-responsive' id='img-preview'>";
                    $text .= '<br><i class="fa fa-male"></i> x ' . $model->carModel['max_seats'] .
                        ' <i class="fa fa-suitcase"></i> x ' . $model->carModel['max_bags'];
                    return $text;
                })
                ->addColumn('action', function ($model) use ($request) {
                    $trashed = (bool)$request->trashed;
                    return BootForm::linkOfEdit('transfers-price.edit', $model->id, $trashed, !$trashed)
                        . BootForm::linkOfRestore('transfers-price.restore', $model->id, 'price', $trashed)
                        . BootForm::linkOfDelete('transfers-price.soft_delete', $model->id, 'price', 'link', true, '', !$trashed)
                        . BootForm::linkOfPermanentDelete('transfers-price.destroy', $model->id, 'price', 'link', true, '', $trashed);

                })->rawColumns(['carModel.model_name', 'action'])
                ->make(true);
        }
        return view('companies.transfer.price_list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $airports = Airport::whereNull('deleted_at')->get()->pluck('name', 'id');
        $car_models = Car_model::whereNull('deleted_at')->get()->pluck('ModelWithSeats', 'id');
        $hotels = Client::where('company_id', auth('admin')->user()->adminable->id)
            ->where('type', 'hotel')
            ->whereNull('deleted_at')->get()->pluck('name', 'id');
        return view('companies.transfer.price_create', compact('hotels', 'airports', 'car_models'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'departure_price' => 'required|numeric',
            'arrival_price' => 'required|numeric',
            'car_model_id' => 'required',
            'hotel_id' => 'required',
            'airport_id' => 'required',
        ]);

        if (Transfer_price_list::where('client_id', $request->hotel_id)
            ->where('car_model_id', $request->car_model_id)
            ->where('airport_id', $request->airport_id)
            ->whereNull('deleted_at')
            ->where('company_id', auth('admin')->user()->adminable->id)->first()) {
            flash()->error('This record already added in transfers price');
            return redirect(route('transfers-price.create'));
        }
        $inputs = $request->all();
        $inputs['company_id'] = auth('admin')->user()->adminable->id;
        if (!Transfer_price_list::create($inputs)) {
            flash()->error('failed to update data, please try again later');
        } else {
            flash()->success('Data was saved successfully');
        }
        return redirect(route('prices-list'));
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
        $transfer_price = Transfer_price_list::find($id);
        $airports = Airport::whereNull('deleted_at')->get()->pluck('name', 'id');
        $car_models = Car_model::whereNull('deleted_at')->get()->pluck('ModelWithSeats', 'id');
        $hotels = Client::where('company_id', auth('admin')->user()->adminable->id)
            ->where('type', 'hotel')
            ->whereNull('deleted_at')->get()->pluck('name', 'id');
        return view('companies.transfer.price_edit', compact('hotels', 'airports', 'car_models', 'transfer_price'));

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
        $request->validate([
            'departure_price' => 'required|numeric',
            'arrival_price' => 'required|numeric',
            'car_model_id' => 'required',
            'hotel_id' => 'required',
            'airport_id' => 'required',
        ]);
        $transfer_price = Transfer_price_list::find($id);
        if (Transfer_price_list::where('client_id', $request->hotel_id)
            ->where('car_model_id', $request->car_model_id)
            ->where('airport_id', $request->airport_id)
            ->where('id', '!=', $id)->whereNull('deleted_at')
            ->where('company_id', auth('admin')->user()->adminable->id)->first()) {
            flash()->error('This record already added in transfers price');
            return redirect(route('transfers-price.edit', $id));
        }
        if (!$transfer_price->update($request->all())) {
            flash()->error('failed to update data, please try again later');
        } else {
            flash()->success('Data was saved successfully');
        }
        return redirect(route('prices-list'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function softDelete($id)
    {
        $transfer_price = Transfer_price_list::findOrFail($id);
        if ($this->softDeleteModel($transfer_price)) {
            $transfer_price->save();
            flash()->success('Data was deleted successfully');
        } else {
            flash()->error('failed to delete data, please try again later');
        }
        return redirect()->route('prices-list');
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
}
