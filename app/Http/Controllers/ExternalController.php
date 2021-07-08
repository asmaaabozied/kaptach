<?php

namespace App\Http\Controllers;

use App\Client;
use App\Country;
use Illuminate\Http\Request;

class ExternalController extends Controller
{
    public function externalTransferRequest($slug)
    {
        $client = Client::where('slug', $slug)->whereNull('deleted_at')->first();
        return view('clients.external.index', compact('client'));
    }

    public function externalBookingTransfer($slug)
    {
        $client = Client::where('slug', $slug)->whereNull('deleted_at')->first();
        $countries = Country::all();
        return view('clients.external.create', compact('client','countries'));
    }
}
