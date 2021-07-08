<?php

namespace App\Http\Controllers\Api;

use App\Admin;
use App\Client;
use App\Driver;
use App\Employer;
use App\Http\Controllers\Controller;
use App\Transfer_price_list;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientsController extends Controller
{
    public $successStatus = 200;

    public function index()
    {
        $auth_id = Auth::guard('api')->user()->id;
        $employer = Employer::findOrFail($auth_id);
        $clients = Client::where('company_id', $employer->driver->company_id)
            ->whereNull('deleted_at')
            ->get();
        $res = [];
        foreach ($clients as $client) {
            $default_img = asset('assets/img/no-image-available.jpg');
            if (empty($client->logo))
                $client_img = $default_img;
            else
                $client_img = $client->logo['thumb'];

            $arr = [
                'id' => $client->id,
                'name' => $client->name,
                'address' => $client->address,
                'location' => [
                    'latitude' => $client->lat,
                    'longitude' => $client->lang,
                ],
                'logo' => $client_img,
            ];
            array_push($res, $arr);
        }
        $data['clients'] = $res;
        return response()->json($data, $this->successStatus);
    }

    public function create(Request $request)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $driver = Driver::findOrFail($auth_id);
        if ($driver->driver_type == 'personal') {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'type' => 'required',
                'station_id' => 'required_if:type,hotel',
                'address' => 'required',
                'lat' => 'required',
                'lang' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            $inputs = $request->all();
            $inputs['company_id'] = $driver->company_id;
            $inputs['slug'] = Client::makeSlug($request->name);
            $client = Client::create($inputs);
            $username = Admin::generateUsername($request->name);
            $client->admins()->create(
                [
                    'role_id' => 1,
                    'username' => $username,
                    'password' => bcrypt('123456'),
                    'email' => $username . '@kaptan.com',
                    'type' => $request->type,
                ]
            );
            $data['client'] = [
                'username' => $username,
                'password' => '123456'
            ];
            return response()->json($data, $this->successStatus);
        } else
            return response()->json(['status' => false], 401);
    }

    public function getTransferPrice(Request $request)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $driver = Driver::findOrFail($auth_id);
        $validator = Validator::make($request->all(), [
            'client_id' => 'required',
            'airport_id' => 'required',
            'car_model_id' => 'required',
            'type' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $type = $request->type;
        $result = Transfer_price_list::where('car_model_id', $request->car_model_id)
            ->where('client_id', $request->client_id)
            ->where('airport_id', $request->airport_id)
            ->where('company_id', $driver->company_id)
            ->whereNull('deleted_at')->first();
        if (isset($result))
            $price = ($type == "arrival") ? $result->arrival_price : $result->departure_price;
        else
            $price = 0;

        return response()->json(['price' => $price]);
    }
}
