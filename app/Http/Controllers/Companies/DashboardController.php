<?php

namespace App\Http\Controllers\Companies;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Company;

class DashboardController extends BaseController
{

    public function index()
    {
        $position = array("lat"=> "41.26596370647999","lng"=> "28.764362733496114");//Istanbul Location could be changed with whatever
        $cars = array(
            (object)array('id' => '1', 'name' => 'Vito 3X4' ,'number' => '12345' , 'lat' => '41.260157084089705', 'lng' => '28.7471965958008', 'status' => 0),
            (object)array('id' => '2', 'name' => 'Vito 2X1' ,'number' => '101010', 'lat' => '41.26596370647999', 'lng' => '28.764362733496114', 'status' => 1),
            (object)array('id' => '3', 'name' => 'BMW 2X1' ,'number' => '332325', 'lat' => '41.25596370647999', 'lng' => '28.754362733496114', 'status' => 2),
            (object)array('id' => '5', 'name' => 'FIAT 2X1' ,'number' => 'xxxxx', 'lat' => '41.28596370647999', 'lng' => '28.764362733496114', 'status' => 1),
            (object)array('id' => '6', 'name' => 'FIAT 3X1' ,'number' => 'vvvvvv', 'lat' => '41.28596370647999', 'lng' => '28.754362733496114', 'status' => 2),
        );  
        $cars_count_by_status = array('1' => 2 ,  '2' => 2 ,  '3' => 0);  
        $hotels = Company::where('type','hotel')->whereNull('deleted_at')->get();
        return view('companies.dashboard',compact('position','cars','cars_count_by_status','hotels'));
    }
    /*public function map()
    {
        $position = array("lat"=> "41.26596370647999","lng"=> "28.764362733496114");//Istanbul Location could be changed with whatever
        $cars = array(
            (object)array('id' => '1', 'name' => 'Vito 3X4' ,'number' => '12345' , 'lat' => '41.260157084089705', 'lng' => '28.7471965958008', 'status' => 0),
            (object)array('id' => '2', 'name' => 'Vito 2X1' ,'number' => '101010', 'lat' => '41.26596370647999', 'lng' => '28.764362733496114', 'status' => 1),
            (object)array('id' => '3', 'name' => 'BMW 2X1' ,'number' => '332325', 'lat' => '41.25596370647999', 'lng' => '28.754362733496114', 'status' => 2),
            (object)array('id' => '5', 'name' => 'FIAT 2X1' ,'number' => 'xxxxx', 'lat' => '41.28596370647999', 'lng' => '28.764362733496114', 'status' => 1),
            (object)array('id' => '6', 'name' => 'FIAT 3X1' ,'number' => 'vvvvvv', 'lat' => '41.28596370647999', 'lng' => '28.754362733496114', 'status' => 2),
        );  
        $cars_count_by_status = array('1' => 2 ,  '2' => 2 ,  '3' => 0);  
        $clients = Company::where('type','hotel')->whereNull('deleted_at')->get();
        return view('companies.map',compact('position','cars','cars_count_by_status','clients'));
    }*/
    public function search($type,$id){
        if ($type == 'hotel'){
            //Find The Cars which are related to the hotel_id
            $hotel = Company::find($id); 
            if ($id == 0 || $id = ""){
            $cars = array(
                (object)array('id' => '1', 'name' => 'Vito 3X4' ,'number' => '12345' , 'lat' => '41.260157084089705', 'lng' => '28.7471965958008', 'status' => 0),
                (object)array('id' => '2', 'name' => 'Vito 2X1' ,'number' => '101010', 'lat' => '41.26596370647999', 'lng' => '28.764362733496114', 'status' => 1),
                (object)array('id' => '3', 'name' => 'BMW 2X1' ,'number' => '332325', 'lat' => '41.25596370647999', 'lng' => '28.754362733496114', 'status' => 2),
                (object)array('id' => '5', 'name' => 'FIAT 2X1' ,'number' => 'xxxxx', 'lat' => '41.28596370647999', 'lng' => '28.764362733496114', 'status' => 1),
                (object)array('id' => '6', 'name' => 'FIAT 3X1' ,'number' => 'vvvvvv', 'lat' => '41.28596370647999', 'lng' => '28.754362733496114', 'status' => 2),
            ); 
            $cars_count_by_status = array('1' => 2 ,  '2' => 2 ,  '3' => 0); 
            }
            else{
                $cars = array(
                    (object)array('id' => '1', 'name' => 'Vito 3X4' ,'number' => '12345' , 'lat' => '41.260157084089705', 'lng' => '28.7471965958008', 'status' => 1),
                    (object)array('id' => '6', 'name' => 'FIAT 3X1' ,'number' => 'vvvvvv', 'lat' => '41.28596370647999', 'lng' => '28.754362733496114', 'status' => 2),
                ); 
                $cars_count_by_status = array('1' => 1 ,  '2' => 1 ,  '3' => 0); 
            }
            $data = array(
                'hotel' => $hotel,
                'cars'  => $cars,
                'cars_count_by_status' => $cars_count_by_status
            );
        }
        if ($type == 'car'){
            //Find The Car which number is $id
            $cars = array(
                (object)array('id' => '1', 'name' => 'Vito 3X4' ,'number' => '12345' , 'lat' => '41.260157084089705', 'lng' => '28.7471965958008', 'status' => 1),
            ); 
            //$cars_count_by_status = array( 1: 'offline' ,2 :'online' ,  3: 'on_the_road' => 3); 
            $cars_count_by_status = array('1' => 1 ,  '2' => 0 ,  '3' => 0); 
            $data = array(
                'cars'  => $cars,
                'cars_count_by_status' => $cars_count_by_status
            );
        }
        if ($type == 'status'){
            //Find The Cars which are online (status= 0),..
            $cars = array(
                (object)array('id' => '1', 'name' => 'Vito 3X4' ,'number' => '12345' , 'lat' => '41.260157084089705', 'lng' => '28.7471965958008', 'status' => intval($id)),
            ); 
            //$cars_count_by_status = array('online' => 2 ,  'offline' => 4 ,  'on_the_road' => 3); 
            $cars_count_by_status = array('1' => (intval($id) == 1)? 1: 0 ,  '2' => (intval($id) == 2)? 1: 0 ,  '3' => (intval($id) == 3)? 1: 0); 
            $data = array(
                'cars'  => $cars,
                'cars_count_by_status' => $cars_count_by_status
            );
        }
        return response()->json($data);
    }


    public function pricesList()
    {
        return view('companies.prices');
    }
}
