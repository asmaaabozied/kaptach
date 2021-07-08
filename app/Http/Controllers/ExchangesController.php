<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Attribute;
use App\Company;
use App\Exchange;
use App\Offer;
use App\Transfer;
use Illuminate\Http\Request;

class ExchangesController extends BaseController
{
    public function searchForExchange($transfer_id)
    {
        $transfer = Transfer::findOrFail($transfer_id);
        $airports = Airport::whereNull('deleted_at')->get();
        return view('companies.transfer.exchange', compact('airports', 'transfer'));
    }

    public function store($transfer_id, Request $request)
    {
        $inputs = $request->all();
        $transfer = Transfer::findOrFail($transfer_id);
        $company = Company::findOrFail(auth('admin')->user()->adminable->id);
        //adding exchange table
        $exchange = new Exchange();
        $exchange->transfer_id = $transfer->id;
        $exchange->driver_id = $transfer->driver_id;
        $exchange->company_id = $transfer->company_id;
        $exchange->exchangeable()->associate($company);
        $exchange->save();
        //searching
        $airport_length = 0;
        $type_length = 0;
        $date_length = 0;
        $counter = 0;
        if (isset($request->airport))
            $airport_length = count($request->airport);
        if (isset($request->type))
            $type_length = count($request->type);
        if (isset($request->from))
            $date_length = count($request->from);

        $counter = max($airport_length, $type_length, $date_length);

        $transfers = Transfer::whereNull('deleted_at')->where('status', 'pending')
            ->where('id', '!=', $transfer_id);

        for ($i = 0; $i < $counter; $i++) {
            $attribute_ar = [];

            if ($i == 0) {
                $transfers->where(function ($query) use ($request, $attribute_ar, $inputs, $i) {
                    if (isset($request->airport[$i]))
                        $query->where('airport_id', $inputs['airport'][$i]);

                    if (isset($request->type)) {
                        if (isset($request->type[$i]))
                            $query->whereIn('type', $inputs['type'][$i]);
                    }
                    if (isset($inputs['from'][$i]) && isset($inputs['to'][$i])) {
                        $query->where('transfer_start_time', '>=', $inputs['from'][$i]);
                        $query->where('transfer_start_time', '<=', $inputs['to'][$i]);
                    }


                });
            } else {
                $transfers->orWhere(function ($query) use ($inputs, $i) {
                    if (isset($request->airport[$i]))
                        $query->where('airport_id', $inputs['airport'][$i]);

                    if (isset($request->type)) {
                        if (isset($request->type[$i]))
                            $query->whereIn('type', $inputs['type'][$i]);
                    }
                    if (isset($inputs['from'][$i]) && isset($inputs['to'][$i])) {
                        $query->where('transfer_start_time', '>=', $inputs['from'][$i]);
                        $query->where('transfer_start_time', '<=', $inputs['to'][$i]);
                    }


                });
            }

            //adding attribute table
            if (isset($request->airport[$i]))
                $attribute_ar['airport_id'] = $request->airport[$i];
            if (isset($request->type[$i]))
                $attribute_ar['type'] = implode(',', $request->type[$i]);
            if (isset($request->from[$i]))
                $attribute_ar['from_date'] = $request->from[$i];
            if (isset($request->to[$i]))
                $attribute_ar['to_date'] = $request->to[$i];

            $attribute_ar['exchange_id'] = $exchange->id;
            Attribute::create($attribute_ar);
        }
        $transfers = $transfers->get();
        $total = Transfer::whereNull('deleted_at')->where('id', '!=', $transfer_id)
            ->where('status', 'pending')->get();
        return view('companies.transfer.result_exchange', compact('transfers', 'total'));
    }

    public function show($id)
    {
        $exchange = Exchange::with('offers')->findOrFail($id);
        return view('companies.transfer.view_exchange', compact('exchange'));
    }

    public function offerAccepted($offer_id)
    {
        $offer = Offer::findOrFail($offer_id);
        $offer->update(['status' => 'accepted']);
        Offer::where('id', '!=', $offer->id)->where('exchange_id', $offer->exchange_id)->update(['status' => 'rejected']);
        $exchange = Exchange::findOrFail($offer->exchange_id);
        $exchange->update(['offer_id' => $offer->id]);

        $transfer_offer_id = $offer->transfer_id;
        Transfer::findOrFail($transfer_offer_id)->update(['company_id' => $exchange->company_id, 'driver_id' => null, 'shift_id' => null]);
        $exchange_offer_id = $exchange->transfer_id;
        Transfer::findOrFail($exchange_offer_id)->update(['company_id' => $offer->company_id, 'driver_id' => null, 'shift_id' => null]);

        flash()->success('Data was saved successfully');
        return redirect(route('transfers.index'));
    }

    public function offerRejected($offer_id)
    {
        $offer = Offer::findOrFail($offer_id);
        $offer->update(['status' => 'rejected']);
        flash()->success('Data was saved successfully');
        return redirect(route('transfers.index'));
    }

    public function applyOffer($exchange_id, Request $request)
    {
        $selected=$request->selected;
        $company = Company::findOrFail(auth('admin')->user()->adminable->id);
        for ($i = 0; $i < count($request->selected); $i++) {
            $offer = new Offer();
            $offer->exchange_id=$exchange_id;
            $offer->company_id=$company->id;
            $offer->transfer_id = $selected[$i];
            $offer->offerable()->associate($company);
            $offer->save();
        }

        return 'true';
    }

    public function getOffers($exchange_id)
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
                'company_id' => $transfer->company_id,
                'company' => $transfer->company,
                'transfer_start_time' => $transfer->transfer_start_time,
                'type' => $transfer->type,
                'airport' => $transfer->airport,
                'transferable' => $transfer->transferable,
                'car_model' => $transfer->car_model,
                'number_of_booking' => $transfer->number_of_booking,
                'deleted_at' => $transfer->deleted_at,
                'p_class' => 'col-md-6',
                'class' => 'box-default',
                'close_offers' => false
            ];
            array_push($res, $data);
        }
        return $res;
    }

    public function getExchangeInfo($exchange_id)
    {
        $exchange = Exchange::with('attributes')->findOrFail($exchange_id);
        return $exchange;
    }

    public function getTransferByCompanyId($exchange_id)
    {
        $exchange = Exchange::findOrFail($exchange_id);
        $company_id = auth('admin')->user()->adminable->id;
        $res_arr = collect();

        foreach ($exchange->attributes as $attribute) {
            $transfers = Transfer::
            with('airport', 'transferable', 'car_model', 'company', 'exchange')
                ->where('status', 'Pending')
                ->where('company_id', $company_id);
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
//            array_push($res_arr, $t);
        }
        return $res_arr;

    }
}
