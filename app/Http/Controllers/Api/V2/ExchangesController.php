<?php

namespace App\Http\Controllers\Api\V2;

use App\Attribute;
use App\Company;
use App\Driver;
use App\Exchange;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RestController;
use App\Offer;
use App\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ExchangesController extends RestController
{
    public function store($transfer_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attributes' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $auth_id = Auth::guard('api')->user()->id;
        $driver = Driver::findOrFail($auth_id);
        $transfer = Transfer::findOrFail($transfer_id);
        $inputs = $request->input('attributes');

        $result = json_decode($inputs);

        //adding exchange table
        $exchange = new Exchange();
        $exchange->transfer_id = $transfer->id;
        $exchange->driver_id = $transfer->driver_id;
        $exchange->company_id = $transfer->company_id;
        $exchange->exchangeable()->associate($driver);
        $exchange->save();

        $transfers = Transfer::whereNull('deleted_at')->where('status', 'pending')
            ->where('id', '!=', $transfer_id);
        for ($i = 0; $i < count($result); $i++) {
            $attribute_ar = [];

            if ($i == 0) {
                $transfers->where(function ($query) use ($result, $i) {
                    if (isset($result[$i]->airport))
                        $query->where('airport_id', $result[$i]->airport);

                    if (isset($result[$i]->type))
                        $query->whereIn('type', $result[$i]->type);

                    if (isset($result[$i]->from_date) && isset($result[$i]->to_date)) {
                        $query->where('transfer_start_time', '>=', $result[$i]->from_date);
                        $query->where('transfer_start_time', '<=', $result[$i]->to_date);
                    }


                });
            } else {
                $transfers->orWhere(function ($query) use ($result, $i) {
                    if (isset($result[$i]->airport))
                        $query->where('airport_id', $result[$i]->airport);

                    if (isset($result[$i]->type))
                        $query->whereIn('type', $result[$i]->type);

                    if (isset($result[$i]->from_date) && isset($result[$i]->to_date)) {
                        $query->where('transfer_start_time', '>=', $result[$i]->from_date);
                        $query->where('transfer_start_time', '<=', $result[$i]->to_date);
                    }


                });
            }

            //adding attribute table
            if (isset($result[$i]->airport))
                $attribute_ar['airport_id'] = $result[$i]->airport;
            if (isset($result[$i]->type))
                $attribute_ar['type'] = implode(',', $result[$i]->type);
            if (isset($result[$i]->from_date))
                $attribute_ar['from_date'] = $result[$i]->from_date;
            if (isset($result[$i]->to_date))
                $attribute_ar['to_date'] = $result[$i]->to_date;

            $attribute_ar['exchange_id'] = $exchange->id;
            Attribute::create($attribute_ar);

        }
        $transfers = $transfers->get();
        return response()->json(['count' => count($transfers)]);

    }

    public function undoExchange($exchange_id)
    {
        $exchange = Exchange::whereNull('offer_id')->find($exchange_id);
        if ($exchange) {
            Attribute::where('exchange_id', $exchange->id)->delete();

            Offer::where('exchange_id', $exchange->id)->delete();

            $exchange->delete();
            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false]);
    }

    public function findMatching($exchange_id)
    {
        $exchange = Exchange::findOrFail($exchange_id);
        $auth_id = Auth::guard('api')->user()->id;
        $driver = Driver::findOrFail($auth_id);
        $res_arr = collect();
        $transfers = Transfer::
        with('airport', 'transferable', 'car_model', 'company', 'exchange','guests')
            ->where('status', 'Pending')
            ->where('company_id', $driver->company_id)
            ->where('id', '!=', $exchange->transfer_id);
        foreach ($exchange->attributes as $attribute) {

            if ($attribute->from_date) {
                $from = $attribute->from_date;
                $to = $attribute->to_date;
                $transfers->where('transfer_start_time', '>=', $from)
                    ->where('transfer_start_time', '<=', $to);
            }
            if ($attribute->airport_id)
                $transfers->where('airport_id', $attribute->airport_id);
            if ($attribute->type)
                $transfers->whereIn('type', explode(',', $attribute->type));

            $transfers->whereNull('deleted_at')
                ->orderBy('transfer_start_time', 'DESC');
            $transfers = $transfers->get();
            $res_arr = $res_arr->merge($transfers);
        }
        $res = $this->transferObject($res_arr);
        return response()->json(['data' => $res]);
    }
}
