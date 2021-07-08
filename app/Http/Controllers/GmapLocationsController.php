<?php

namespace App\Http\Controllers;

use App\Airport;
use App\Client;
use Illuminate\Http\Request;

class GmapLocationsController extends Controller
{
    public function getLocations()
    {
        $result=[];
        $client = $this->getClientPoints();
        $airports = $this->getAirportsPoints();
//        $result = array_push($result, $airports);
//        print_r($airports);
        return json_encode($airports);
    }

    public function getClientPoints($id = null)
    {
        if ($id == null)
            $client = Client::findOrFail(auth('admin')->user()->adminable->id);
        else
            $client = Client::findOrFail($id);

        return [
            'id' => $client->id,
            'name' => $client->name,
            'address' => $client->address,
            'lat' => $client->lat,
            'lang' => $client->lang
        ];
    }

    public function getAirportsPoints()
    {
        $result = [];
        $airports = Airport::whereNull('deleted_at')->get();
        foreach ($airports as $airport) {
            $data = [
                'id' => $airport->id,
                'name' => $airport->name,
                'address' => $airport->address,
                'lat' => $airport->lat,
                'lang' => $airport->lang
            ];
            array_push($result, $data);
        }
        return $result;
    }
}
