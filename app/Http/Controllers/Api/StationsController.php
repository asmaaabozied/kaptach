<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Station;
use Illuminate\Http\Request;

class StationsController extends Controller
{
    public $successStatus = 200;
    public function index()
    {
        $data['stations'] = Station::select('id', 'name')
            ->where('status','1')
            ->whereNull('deleted_at')
            ->get();
        return response()->json($data, $this->successStatus);
    }
}
