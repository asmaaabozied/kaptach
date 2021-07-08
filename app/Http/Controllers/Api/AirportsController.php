<?php

namespace App\Http\Controllers\Api;

use App\Airport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AirportsController extends Controller
{
    public $successStatus = 200;

    public function index()
    {
        $airports = Airport::whereNull('deleted_at')->get();
        $res = [];
        foreach ($airports as $airport) {
            $default_img = asset('assets/img/no-image-available.jpg');
            if (empty($airport->arrival_image['thumb']))
                $airport_arrival_img = $default_img;
            else
                $airport_arrival_img = $airport->arrival_image['thumb'];

            if (empty($airport->departure_image['thumb']))
                $airport_departure_img = $default_img;
            else
                $airport_departure_img = $airport->departure_image['thumb'];


            $arr = [
                'id' => $airport->id,
                'name' => $airport->name,
                'location' => [
                    'latitude' => $airport->lat,
                    'longitude' => $airport->lang,
                ],
                'arrival_image' => $airport_arrival_img,
                'departure_image' => $airport_departure_img
            ];
            array_push($res,$arr);
        }
        $data['airports'] =$res;
        return response()->json($data, $this->successStatus);
    }
}
