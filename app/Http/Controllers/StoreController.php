<?php

namespace App\Http\Controllers;

use App\Car;
use App\Client;
use App\Company;
use App\Driver;
use App\Events\TransferCreated;
use App\Events\TransferRemoved;
use App\Shift;
use App\Store;
use App\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StoreController extends BaseController
{
    public function index(Request $request)
    {
        $query = Transfer::query();
        $query->with('store')
            ->whereNull('deleted_at')
            ->open()
            ->where('company_id', auth('admin')->user()->adminable->id)
            ->orderBy('transfer_start_time', 'ASC');
        if ($request->input('shift') != '') {
            $query->whereNotNull('shift_id');
        } else
            $query->whereNull('shift_id');

        $query->get();
        if ($request->input('hotel_id') != '') {
            $query->client($request->hotel_id);
        }
        if ($request->input('from') != '') {
            $query->where('transfer_start_time', '>=', $request->input('from'));
        }
        if ($request->input('to') != '') {
            $query->whereDate('transfer_start_time', '<=', $request->input('to'));
        }

        /*get limit per page*/
        $limit = 10;
        //paginate data
        $transfers = $query->paginate($limit);
        //appends other url parameters for pagination
        $transfers->appends($request->except(['page']));
        $hotels = Client::where('company_id', auth('admin')->user()->adminable->id)
            ->where('type', 'hotel')->whereNull('deleted_at')->get()->pluck('name', 'id');
        $t_offered_for_buy = Transfer::has('store')
            ->where('company_id', '!=', auth('admin')->user()->adminable->id)
            ->orderBy('transfer_start_time')->paginate(10);

        $t_offered_for_sale = Transfer::has('store')
            ->where('company_id', auth('admin')->user()->adminable->id)
            ->orderBy('transfer_start_time')->paginate(10);

        return view('companies.transfer.store', compact('transfers', 'hotels', 't_offered_for_buy', 't_offered_for_sale'));
    }

    public function offerForSale(Request $request)
    {
        $ids = $request->transfer_ids;

        if (count($request->sale_for) == 2)
            $sale_for = 3;
        else
            $sale_for = $request->sale_for[0];

        for ($i = 0; $i < count($ids); $i++) {
            $transfer = Transfer::with('airport', 'transferable', 'car_model',
                'forSalesCompanies', 'company')
                ->findOrFail($ids[$i]);
            $employer_id = '';

            $employer_id = $transfer->driver_id;

            Store::create([
                'transfer_id' => $ids[$i],
                'store_for' => $sale_for,
                'seller_id' => auth('admin')->user()->adminable->id,
                'seller_type' => 'App\Company',
                'company_id' => auth('admin')->user()->adminable->id,
                'driver_id' => $employer_id,
                'type' => 'sale'
            ]);

//            if ($sale_for == 2 || $sale_for == 3)
//                event(new TransferCreated($transfer));
//                TransferCreated::dispatch($transfer);
        }

        return 'true';

    }

    public function undoOfferForSale(Request $request)
    {
        $store = Store::findOrFail($request->id);
        if ($store->delete()) {
            return 'true';
        } else
            return 'false';
    }

    public function transfersOfferedForSale(Request $request)
    {

        $res_arr = $this->transferQuery();
        if ($request->page == Null)
            $request->page = 1;
        $arr = $res_arr->forPage($request->page, 10);
        $res = [];
        $company_id = auth('admin')->user()->adminable->id;
        foreach ($arr as $value) {
            $data = [
                'id' => $value->id,
                'company_id' => $value->company_id,
                'company' => $value->company,
                'transfer_start_time' => $value->transfer_start_time,
                'type' => $value->type,
                'airport' => $value->airport,
                'transferable' => $value->transferable,
                'car_model' => $value->car_model,
                'number_of_booking' => $value->number_of_booking,
                'deleted_at' => $value->deleted_at
            ];
            if ($value->company_id == $company_id) {
                if (!$value->forSalesCompanies->isEmpty()) {
                    foreach ($value->forSalesCompanies as $forsale) {
                        if ($forsale->buyable_id == $company_id)
                            $data['purchased'] = 'purchased';
                        if ($forsale->company_id == $company_id && $forsale->buyable_id == "") {
                            $data['for_sale'] = 'For sale';
                        }
                        if ($forsale->company_id == $company_id && $forsale->buyable_id != "")
                            $data['sold'] = 'Sold';

                        $data['store_id'] = $forsale->id;
                    }
                }
                if ($value->exchange) {
                    $data['for_exchange'] = 'For exchange';
                    $data['exchange_id'] = $value->exchange->id;
                    $data['close_offers'] = false;
                }

                $data['p_class'] = 'col-md-6 col-md-offset-6';
                $data['class'] = 'box-success';
            } else {
                foreach ($value->forSalesCompanies as $forsale) {
                    if ($forsale->buyable_id == $company_id)
                        $data['purchased'] = 'purchased';
                    if ($forsale->company_id != $company_id && $forsale->buyable_id == "")
                        $data['for_sale'] = 'For sale';
                    if ($forsale->company_id == $company_id && $forsale->buyable_id != "")
                        $data['sold'] = 'Sold';

                    $data['store_id'] = $forsale->id;
                }

                if ($value->exchange) {
                    $data['for_exchange'] = 'For exchange';
                    $data['close_offers'] = false;
                    $data['exchange_id'] = $value->exchange->id;
                }


                $data['p_class'] = 'col-md-6';
                $data['class'] = 'box-danger';
            }

            array_push($res, $data);
        }

        return $res;
//        return response()->json([
//            'data' => $res,
//            'length' => $arr->count()
//        ]);
    }

    private function transferQuery()
    {
//        $cars = Car::with('carModel')
//            ->where('company_id', auth('admin')->user()->adminable->id)
//            ->whereNull('deleted_at')->get();
        $car_models = DB::table('car_models')
            ->join('car_car_model', 'car_car_model.car_model_id', '=', 'car_models.id')
            ->join('cars', 'cars.id', '=', 'car_car_model.car_id')
            ->where('cars.company_id', auth('admin')->user()->adminable->id)
            ->whereNull('car_models.deleted_at')
            ->pluck('car_models.id')->toArray();

        $t_with_offered_for_sale = Transfer::with('airport', 'transferable',
            'car_model', 'company', 'forSalesCompanies', 'exchange')
            ->start()->DateFilter()
            ->whereNull('deleted_at')
            ->where('company_id', auth('admin')->user()->adminable->id)
            ->orderBy('transfer_start_time', 'DESC')->get();
        $company = Company::findOrFail(auth('admin')->user()->adminable->id);

        $t_sold_transfer = Transfer::whereHas('forSalesCompanies', function ($q) use ($company) {
            $q->where('buyable_id', $company->id)->where('buyable_type', 'App\Company')
                ->orWhere('company_id', $company->id);
        })
            ->with('airport', 'transferable', 'car_model', 'company', 'forSalesCompanies')
            ->DateFilter()
            ->whereNull('deleted_at')
            ->start()
            ->orderBy('transfer_start_time', 'DESC')->get();

        $t_offered_for_sale = Transfer::whereHas('forSalesCompanies', function ($q) {
            $q->whereNull('buyable_id');
        })->DateFilter()
            ->with('airport', 'transferable', 'car_model', 'company', 'forSalesCompanies')
            ->start()
            ->whereNull('deleted_at')
            ->where('company_id', '!=', auth('admin')->user()->adminable->id)
            ->whereIn('car_model_id', $car_models)
            ->orderBy('transfer_start_time', 'DESC')->get();

        $transfer_offered_for_exchange = Transfer::whereHas('exchange', function ($q) {
            $q->whereNull('offer_id');
        })->DateFilter()
            ->start()
            ->with('airport', 'transferable', 'car_model', 'company', 'exchange')
            ->where('status', 'pending')->whereNull('deleted_at')
            ->where('company_id', '!=', auth('admin')->user()->adminable->id)
            ->orderBy('transfer_start_time', 'DESC')->get();

        $transfers = $t_with_offered_for_sale->merge($t_offered_for_sale)->merge($t_sold_transfer)
            ->merge($transfer_offered_for_exchange)
            ->sortBy('transfer_start_time');

        return $transfers;
    }

    public function buy($transfer_id, Request $request)
    {
        $company_id = $request->company_id;
        $transfer = Transfer::with('airport', 'transferable', 'car_model', 'company', 'forSalesCompanies')
            ->findOrFail($transfer_id);
        $company = Company::findOrFail($company_id);
        $res = $transfer->store->buyable()->associate($company)->update();
        Store::where('transfer_id', $transfer_id)->where('id', '!=', $transfer->Store->id)->delete();
        $array_updates = [
            'sold' => 1,
            'company_id' => $company_id];
        //Delete shift after buy
        if ($transfer->shift) {
            $array_updates['shift_id'] = NULL;

        }
        if ($company->type == 'personal') {
            $date = $transfer->datetime;
            $car = Car::whereHas('carModel', function ($query) use ($transfer) {
                $query->where('car_model_id', $transfer->car_model_id);
            })->whereNull('deleted_at')
                ->where('company_id', $company_id)
                ->first();
            if (!$car) {
                flash()->error('Please add car for this model!');
                return redirect(route('cars.create'));
            } else {
                $driver = Driver::where('company_id', $company->id)->whereNull('deleted_at')->first();

                $shift_inputs = ['shift_start_time' => $date,
                    'shift_end_time' => $date,
                    'car_id' => $car->id,
                    'driver_id' => $driver->id,
                    'company_id' => $company->id,
                ];
                $shift = Shift::create($shift_inputs);
                $array_updates['shift_id'] = $shift->id;
                $array_updates['car_id'] = $car->id;
                $array_updates['driver_id'] = $driver->id;
            }
        }
        $transfer->update($array_updates);
        TransferRemoved::dispatch($transfer);
        return 'true';
//        if ($res)
//            return $transfer;
//        else
//            return 'false';
    }

    public function cancelOfferForSale($store_id)
    {
        $store = Store::findOrFail($store_id);
        $transfer = Transfer::findOrFail($store->id);
        $transfer->update(['company_id' => $store->company_id, 'driver_id' => $store->driver_id]);
        if ($store->delete()) {
            flash()->success('Data was saved successfully');
        } else
            flash()->error('failed to update data, please try again later');
        return redirect(route('transfers.index'));
    }
}
