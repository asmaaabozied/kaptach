<?php

namespace App\Http\Controllers\Companies;

use App\Airport;
use App\Client;
use App\Company;
use App\Helpers\BootForm;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Traits\SoftDeleteTrait;
use App\Shuttle_price_list;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ShuttlesPriceController extends BaseController
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

            $shuttle_prices = Shuttle_price_list::with('clients', 'airports')
                ->where('company_id', auth('admin')->user()->adminable->id)
                ->whereNull('deleted_at')->get();

            return Datatables::of($shuttle_prices)
                ->addColumn('action', function ($model) use ($request) {
                    $trashed = (bool)$request->trashed;
                    return BootForm::linkOfEdit('shuttles-price.edit', $model->id, $trashed, !$trashed)
                        . BootForm::linkOfRestore('shuttles-price.restore', $model->id, 'price', $trashed)
                        . BootForm::linkOfDelete('shuttles-price.soft_delete', $model->id, 'price', 'link', true, '', !$trashed)
                        . BootForm::linkOfPermanentDelete('shuttles-price.destroy', $model->id, 'price', 'link', true, '', $trashed);

                })
                ->make(true);
        }
        return view('companies.shuttle.price_list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hotels = Client::where('company_id', auth('admin')->user()->adminable->id)
            ->where('type', 'hotel')
            ->whereNull('deleted_at')->get()->pluck('name', 'id');
        $airports = Airport::whereNull('deleted_at')->get()->pluck('name', 'id');
        return view('companies.shuttle.price_create', compact('hotels', 'airports'));
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
            'hotel_id' => 'required',
            'airport_id' => 'required',
        ]);
        if (Shuttle_price_list::where('client_id', $request->hotel_id)
            ->where('airport_id', $request->airport_id)
            ->where('company_id', auth('admin')->user()->adminable->id)
            ->whereNull('deleted_at')->first()) {
            flash()->error('This Hotel already had a price');
            return redirect(route('shuttles-price.create'));
        }
        $inputs = $request->all();
        $inputs['company_id'] = auth('admin')->user()->adminable->id;
        if (!Shuttle_price_list::create($inputs)) {
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
        $shuttle_price = Shuttle_price_list::find($id);
        $hotels = Client::where('company_id', auth('admin')->user()->adminable->id)
            ->where('type', 'hotel')
            ->whereNull('deleted_at')->get()->pluck('name', 'id');
        $airports = Airport::whereNull('deleted_at')->get()->pluck('name', 'id');
        return view('companies.shuttle.price_edit', compact('hotels', 'shuttle_price', 'airports'));
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
            'hotel_id' => 'required',
            'airport_id' => 'required',
        ]);
        $shuttle_price = Shuttle_price_list::find($id);

        if (Shuttle_price_list::where('client_id', $request->hotel_id)
            ->where('airport_id', $request->airport_id)
            ->where('company_id', auth('admin')->user()->adminable->id)
            ->where('id', '!=', $id)->whereNull('deleted_at')->first()) {
            flash()->error('This Hotel already had a price');
            return redirect(route('shuttles-price.edit', $id));
        }
        if (!$shuttle_price->update($request->all())) {
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
        $shuttle_price = Shuttle_price_list::findOrFail($id);
        if ($this->softDeleteModel($shuttle_price)) {
            $shuttle_price->save();
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

    function getShuttlePriceList(Request $request)
    {
        $hotel_id = $request->hotel_id;
        $airport_id = $request->airport_id;
        $type = $request->type;
        $result = Shuttle_price_list::where('client_id', $hotel_id)->
        where('airport_id', $airport_id)->whereNull('deleted_at')
            ->where('company_id',auth('admin')->user()->adminable->id)->first();
        if (isset($result))
            $price = ($type == "arrival") ? $result->arrival_price : $result->departure_price;
        else
            $price = 0;
        $data = array('price' => $price);
        return response()->json($data);

    }
}
