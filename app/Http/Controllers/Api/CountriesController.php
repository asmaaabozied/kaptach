<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CountriesController extends Controller
{
    public $successStatus = 200;
    public function index()
    {
        $data['countries'] = Country::select('phonecode', 'nationality','en_short_name')->get();
        return response()->json($data, $this->successStatus);
    }
}
