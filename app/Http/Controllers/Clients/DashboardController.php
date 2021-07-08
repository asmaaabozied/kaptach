<?php

namespace App\Http\Controllers\Clients;

use App\Airport;
use App\Car_model;
use App\Client;
use App\Company;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends BaseController
{
    public function index()
    {
        $client_id = auth('admin')->user()->adminable->id;
        $car_models = Car_model::whereNull('deleted_at')->get();
        $airports = Airport::whereNull('deleted_at')->get();
        $client = Client::find($client_id);
        //$hotel_name = $hotel->name;
        return view('clients.index', compact('car_models', 'airports', 'client'));
    }

    public function changePosition($type, $id)
    {
        /*$car_models = Car_model::whereNull('deleted_at')->get();
        $airports = Airport::whereNull('deleted_at')->get();
        $hotel = Company::find(auth('admin')->user()->corporate_id);
        $start = "";    $end ="";
        //$start = $hotel->address;         
        $position = array("lat"=> $hotel->lat,"lng"=> $hotel->lng);
        if ($type == "airport"){
            $airport = Airport::findOrFail($id);
            //$end = $airport->address;  
            $position = array("lat"=> $airport->lat,"lng"=> $airport->lng);  
        }   
        return view('clients.index', compact('car_models', 'airports', 'hotel', 'position','start','end'));*/
        /*$data =   Airport::whereNull('deleted_at')->get(); 
        $data = $data->toArray();    
        return response()->json(['data' => view('clients.index')->with('results',$data)->render()]);*/
        if ($type == 'airport')
            $data = Airport::findOrFail($id);
        else
            $data = Client::findOrFail($id);
        return response()->json($data);
    }

    /*public function changePosition($id){
        $airportId = $id;
        $airport = Airport::findOrFail($airportId);
        $lat = $airport->lat;
        $lng = $airport->lng;
        $position = array('lat'=> $lat,'lng'=>$lng);    //TODO: Change to get airport position and replave on map
        $car_models = Car_model::whereNull('deleted_at')->get();
        $airports = Airport::whereNull('deleted_at')->get();
        $hotel = Company::whereNull('deleted_at')->find(auth('admin')->user()->corporate_id)->name;
        return view('clients.index',compact('car_models','airports','hotel','position'));

    }*/


}
