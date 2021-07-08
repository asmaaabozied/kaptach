<?php

namespace App\Http\Controllers\Api;

use App\Driver;
use App\Employer;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RestController;
use App\Shift;
use App\Store;
use App\Transfer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends RestController
{
    public function forSale(Request $request)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $employer = Employer::findOrFail($auth_id);

        $query = Transfer::has('forSaleDrivers')
//            ->where('corporate_id', $employer->corporate_id)
            ->with('car_model', 'car')
            ->where('request_status', 1)
            ->whereNull('deleted_at')
            ->where('driver_id', '!=', $auth_id);
        $query->where('status', 'Pending');
        $orderBy = 'transfer_start_time';
        $orderType = 'ASC';
        if ($request->has($orderBy)) {
            $orderBy = $request->orderby;
        }
        if ($request->has('ordertype')) {
            $orderType = $request->ordertype;
        }
        $query->open()->orderBy($orderBy, $orderType);
        if (!$request->page)
            $page = 1;
        else
            $page = (int)$request->page;

        $result = $query->paginate();

        $data = [];
        foreach ($result as $item) {

            $guest_ar = [];
            foreach ($item->guests as $guest) {
                $guest_ar[] = [
                    'identity_number' => $guest->identity_number,
                    'first_name' => $guest->first_name,
                    'last_name' => $guest->last_name,
                    'gender' => $guest->gender,
                    'phone' => $guest->phone,
                    'nationality' => $guest->nationality,
                    'room_number' => $guest->pivot->room_number
                ];
            }
            $cars = NULL;
            if ($item->car) {
                $cars = [
                    'id' => $item->car->id,
                    'name' => $item->car->name,
                    'plate_number' => $item->car->plate_number,
                    'color' => $item->car->color,
                    'licence_plate' => $item->car->licence_plate,
                    'brand' => $item->car->brand,
                    'manufacture_year' => $item->car->manufacture_year,
                    'model' => ['id' => $item->car_model->id,
                        'name' => $item->car_model->model_name,
                        'description' => $item->car_model->model_description,
                        'bags' => $item->car_model->max_bags,
                        'seats' => $item->car_model->max_seats,
                        'original' => $item->car_model->image['original']
                    ]
                ];
            }

            $default_img = asset('assets/img/no-image-available.jpg');
            if (empty($item->airport->arrival_image['thumb']))
                $airport_arrival_img = $default_img;
            else
                $airport_arrival_img = $item->airport->arrival_image['thumb'];

            if (empty($item->airport->departure_image['thumb']))
                $airport_departure_img = $default_img;
            else
                $airport_departure_img = $item->airport->departure_image['thumb'];

            if (empty($item->shift->employer->image))
                $employer_image = $default_img;
            else
                $employer_image = url('uploads/drivers/' . $item->shift->employer->image);


            $array = [
                'id' => $item->id,
                'status' => $item->request_status,
                'number_of_booking' => $item->number_of_booking,
                'driver_acceptance' => $item->driver_acceptance,
                't_status' => $item->status,
                'type' => $item->type,
                'start_date' => $item->transfer_start_time,
                'end_date' => $item->transfer_end_time,
                'airport' => [
                    'id' => $item->airport->id,
                    'name' => $item->airport->name,
                    'address' => $item->airport->address,
                    'location' => [
                        'latitude' => $item->airport->lat,
                        'longitude' => $item->airport->lang,
                    ],
                    'arrival_image' => $airport_arrival_img,
                    'departure_image' => $airport_departure_img
                ],
                'hotel' => [
                    'id' => $item->transferable->id,
                    'name' => $item->transferable->name,
                    'address' => $item->transferable->address,
                    'location' => [
                        'latitude' => $item->transferable->lat,
                        'longitude' => $item->transferable->lang,
                    ]
                ],
                'flight' => [
                    'flight_number' => $item->flight_number,
                    'departure_time' => $item->flight_departure_time,
                    'arrival_time' => $item->flight_arrival_time
                ],
                'car' => $cars,
                'guests' => $guest_ar
            ];
            if ($item->statuses) {
                foreach ($item->statuses as $status)
                    $array['transfer_status'][] = $status->status;
            }
            array_push($data, $array);
        }

        $d = [
            'current_page' => $result->currentPage(),
            'data' => $data,
            'next_page_url' => $result->nextPageUrl(),
            'url' => $result->url($page),
            'per_page' => $result->perPage(),
            'last_page' => $result->lastPage(),
            'count' => $result->count(),
            'total' => $result->total(),

        ];
        return $this->sendResults($d);
    }

    public function sold(Request $request)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $employer = Employer::findOrFail($auth_id);
        $query = Store::with('transfer')
            ->where('driver_id', $auth_id)
            ->where('buyable_id', '!=', 'NULL');

        if (!$request->page)
            $page = 1;
        else
            $page = (int)$request->page;

        $result = $query->paginate();
        $data = [];
        foreach ($result as $item) {
            $guest_ar = [];
            foreach ($item->transfer->guests as $guest) {
                $guest_ar[] = [
                    'identity_number' => $guest->identity_number,
                    'first_name' => $guest->first_name,
                    'last_name' => $guest->last_name,
                    'gender' => $guest->gender,
                    'phone' => $guest->phone,
                    'nationality' => $guest->nationality,
                    'room_number' => $guest->pivot->room_number
                ];
            }
            $cars = NULL;
            if ($item->transfer->car != NULL) {
                $cars = [
                    'id' => $item->transfer->car->id,
                    'name' => $item->transfer->car->name,
                    'plate_number' => $item->transfer->car->plate_number,
                    'color' => $item->transfer->car->color,
                    'licence_plate' => $item->transfer->car->licence_plate,
                    'brand' => $item->transfer->car->brand,
                    'manufacture_year' => $item->transfer->car->manufacture_year,
                    'model' => ['id' => $item->transfer->car_model->id,
                        'name' => $item->transfer->car_model->model_name,
                        'description' => $item->transfer->car_model->model_description,
                        'bags' => $item->transfer->car_model->max_bags,
                        'seats' => $item->transfer->car_model->max_seats,
                        'original' => $item->transfer->car_model->image['original']
                    ]
                ];
            }

            $default_img = asset('assets/img/no-image-available.jpg');
            if (empty($item->airport->arrival_image['thumb']))
                $airport_arrival_img = $default_img;
            else
                $airport_arrival_img = $item->airport->arrival_image['thumb'];

            if (empty($item->airport->departure_image['thumb']))
                $airport_departure_img = $default_img;
            else
                $airport_departure_img = $item->airport->departure_image['thumb'];

            if (empty($item->transfer->shift->employer->image))
                $employer_image = $default_img;
            else
                $employer_image = url('uploads/drivers/' . $item->transfer->shift->employer->image);

            $array = [
                'id' => $item->transfer->id,
                'status' => $item->transfer->status,
                'number_of_booking' => $item->transfer->number_of_booking,
                'driver_acceptance' => $item->transfer->driver_acceptance,
                'type' => $item->transfer->type,
                'start_date' => $item->transfer->transfer_start_time,
                'end_date' => $item->transfer->transfer_end_time,
                'airport' => [
                    'id' => $item->transfer->airport->id,
                    'name' => $item->transfer->airport->name,
                    'address' => $item->transfer->airport->address,
                    'location' => [
                        'latitude' => $item->transfer->airport->lat,
                        'longitude' => $item->transfer->airport->lang,
                    ],
                    'arrival_image' => $airport_arrival_img,
                    'departure_image' => $airport_departure_img
                ],
                'hotel' => [
                    'id' => $item->transfer->transferable->id,
                    'name' => $item->transfer->transferable->name,
                    'address' => $item->transfer->transferable->address,
                    'location' => [
                        'latitude' => $item->transfer->transferable->lat,
                        'longitude' => $item->transfer->transferable->lang,
                    ]
                ],
                'flight' => [
                    'flight_number' => $item->transfer->flight_number,
                    'departure_time' => $item->transfer->flight_departure_time,
                    'arrival_time' => $item->transfer->flight_arrival_time
                ],
                'car' => $cars,
                'guests' => $guest_ar,

            ];

            if ($item->type == 'arrival') {
                if (empty($item->transfer->host->image))
                    $host_image = $default_img;
                else
                    $host_image = url('uploads/hosts/' . $item->transfer->host->image);
                if ($item->host_id)
                    $array['host'] = ['id' => $item->transfer->host->id,
                        'first_name' => $item->transfer->host->first_name,
                        'last_name' => $item->transfer->host->last_name,
                        'phone' => $item->transfer->host->phone,
                        'gender' => $item->transfer->host->gender,
                        'image' => $host_image
                    ];
            }
            if ($item->shift_id != NULL) {
                $array['driver'] = [
                    'id' => $item->transfer->shift->employer->id,
                    'first_name' => $item->transfer->shift->employer->first_name,
                    'last_name' => $item->transfer->shift->employer->last_name,
                    'phone' => $item->transfer->shift->employer->phone,
                    'gender' => $item->transfer->shift->employer->gender,
                    'image' => $employer_image
                ];
            }
            if ($item->statuses) {
                foreach ($item->transfer->statuses as $status)
                    $array['transfer_status'][] = $status->status;
            }
            array_push($data, $array);
        }
        $d = [
            'current_page' => $result->currentPage(),
            'data' => $data,
            'next_page_url' => $result->nextPageUrl(),
            'url' => $result->url($page),
            'per_page' => $result->perPage(),
            'last_page' => $result->lastPage(),
            'count' => $result->count(),
            'total' => $result->total(),

        ];
        return $this->sendResults($d);
    }

    public function offerForSale($id)
    {
        $transfer = Transfer::findOrFail($id);
        $auth_id = Auth::guard('api')->user()->id;
        if ($transfer->driver_acceptance == '1') {
            Store::create([
                'transfer_id' => $id,
                'store_for' => '3',
                'seller_id' => $auth_id,
                'seller_type' => 'App/Driver',
                'company_id' => $transfer->company_id,
                'driver_id' => $auth_id,
                'type' => 'sale'
            ]);
            return response()->json(['status' => true]);
        } else

            return response()->json(['status' => false]);

    }

    public function undoOfferForSale($id)
    {
        $transfer = Transfer::findOrFail($id);
        $auth_id = Auth::guard('api')->user()->id;

        if ($transfer->store != NULL) {
            $transfer->store()->where('driver_id', $auth_id)
                ->whereNull('buyable_id')->delete();
            return response()->json(['status' => true]);
        } else
            return response()->json(['status' => false]);
    }

    public function buy($id)
    {
        $transfer = Transfer::with('store')->findOrFail($id);
        $auth_id = Auth::guard('api')->user()->id;
        $driver = Driver::findOrFail($auth_id);
        $transfer->store->buyable()->associate($driver)->update();
        Store::where('transfer_id', $id)->where('id', '!=', $transfer->Store->id)->delete();
        $transfer->update(['sold', 1, 'driver_id' => $auth_id,'company_id'=>$driver->company_id]);
        //Convert shift to new driver
        if ($transfer->shift) {
            $shift = Shift::findOrFail($transfer->shift_id);
            $shift->update(['driver_id' => $auth_id,'company_id'=>$driver->company_id]);
        }
        return response()->json(['status' => true]);
    }
}
