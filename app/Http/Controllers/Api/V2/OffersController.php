<?php

namespace App\Http\Controllers\Api\V2;

use App\Driver;
use App\Exchange;
use App\Http\Controllers\Controller;
use App\Offer;
use App\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class OffersController extends Controller
{
    public function getOffers($exchange_id, Request $request)
    {
        $exchange = Exchange::findOrFail($exchange_id);
        $res = [];
        $transfers = $exchange->offers->pluck('transfer_id');
        $t = Transfer::whereIn('id', $transfers)
            ->orderBy('transfer_start_time', 'DESC')->pluck('id');
        foreach ($t as $key => $value) {
            $transfer = Transfer::with(['offer' => function ($q) use ($exchange_id) {
                $q->where('exchange_id', $exchange_id);
            }])->findOrFail($value);
            $data = [
                'id' => $transfer->id,
                'offer_id' => $transfer->offer->id,
                'offer_status'=>$transfer->offer->status,
                'company_id' => $transfer->company_id,
                'company_name' => $transfer->company->name,
                'transfer_start_time' => $transfer->transfer_start_time,
                'type' => $transfer->type,
                'airport' => $transfer->airport->name,
                'transferable' => $transfer->transferable->name,
                'car_model' => $transfer->car_model->model_name,
                'number_of_booking' => $transfer->number_of_booking,
            ];
            array_push($res, $data);
        }
        return $res;
    }

    public function applyOffer($exchange_id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|json',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $selected = json_decode($request->ids);

        Exchange::findOrFail($exchange_id);

        $auth_id = Auth::guard('api')->user()->id;
        $driver = Driver::findOrFail($auth_id);

        for ($i = 0; $i < count($selected); $i++) {
            $transfer = Transfer::findOrFail($selected[$i]);
            $offer = new Offer();
            $offer->exchange_id = $exchange_id;
            $offer->company_id = $driver->company_id;
            $offer->transfer_id = $selected[$i];
            $offer->offerable()->associate($driver);
            $offer->save();
        }

        return response()->json(['status' => true]);
    }

    public function undoApplyOffer($offer_id)
    {
        $offer = Offer::where('status', 'pending')->find($offer_id);
        if ($offer) {
            $offer->delete();
            return response()->json(['status' => true]);
        }
        return response()->json(['status' => false]);
    }

    public function offerAccepted($offer_id)
    {
        $offer = Offer::findOrFail($offer_id);
        $offer->update(['status' => 'accepted']);
        Offer::where('id', '!=', $offer->id)
            ->where('exchange_id', $offer->exchange_id)->update(['status' => 'rejected']);
        $exchange = Exchange::findOrFail($offer->exchange_id);
        $exchange->update(['offer_id' => $offer->id]);

        $transfer_offer_id = $offer->transfer_id;
        Transfer::findOrFail($transfer_offer_id)->update(['company_id' => $exchange->company_id, 'driver_id' => null, 'shift_id' => null]);
        $exchange_offer_id = $exchange->transfer_id;
        Transfer::findOrFail($exchange_offer_id)->update(['company_id' => $offer->company_id, 'driver_id' => null, 'shift_id' => null]);

        return response()->json(['status' => true]);
    }

    public function offerRejected($offer_id)
    {
        $offer = Offer::findOrFail($offer_id);
        $offer->update(['status' => 'rejected']);
        return response()->json(['status' => true]);
    }
}
