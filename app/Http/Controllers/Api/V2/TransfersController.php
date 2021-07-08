<?php

namespace App\Http\Controllers\Api\V2;

use App\Admin;
use App\Airport;
use App\Car;
use App\Car_model;
use App\Client;
use App\Company;
use App\Device;
use App\Driver;
use App\Employer;
use App\Events\TransferCreated;
use App\Guest;
use App\Helpers\Notify;
use App\Helpers\PushApi;
use App\Host;
use App\Http\Controllers\RestController;
use App\Status;
use App\Store;
use App\Transfer;
use App\Shift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransfersController extends RestController
{
    public function index(Request $request)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $employer = Employer::findOrFail($auth_id);
        $query = Transfer:: with('car_model', 'car')
            ->where('request_status', 1)
            ->whereNull('deleted_at');
        if ($employer->type == "driver") {
            if (!$request->page)
                $page = 1;
            else
                $page = (int)$request->page;

            $result = $this->transferQuery($request);
            $arr = $result->forPage($request->page, 10);
            $data = [];
            foreach ($arr as $item) {
                $default_img = asset('assets/img/no-image-available.jpg');
                if (empty($item->airport->arrival_image['thumb']))
                    $airport_arrival_img = $default_img;
                else
                    $airport_arrival_img = $item->airport->arrival_image['thumb'];

                if (empty($item->airport->departure_image['thumb']))
                    $airport_departure_img = $default_img;
                else
                    $airport_departure_img = $item->airport->departure_image['thumb'];

                //guests
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

                //cars
                $cars = NULL;
                if ($item->car != NULL) {
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
                // driver profile pic
                if (empty($item->driver->employer->profile_pic))
                    $driver_profile_pic = $default_img;
                else
                    $driver_profile_pic = url('uploads/drivers/' . $item->driver->employer->profile_pic);


                // Buy and sell
                $status_trading = [];
                $seller_type = '';
                $exchangeable_type = '';
                $driver = NULL;
                if ($item->driver_id) {
                    $driver = [
                        'id' => $item->driver->id,
                        'first_name' => $item->driver->employer->first_name,
                        'last_name' => $item->driver->employer->last_name,
                        'phone' => $item->driver->employer->phone,
                        'gender' => $item->driver->employer->gender,
                        'image' => $driver_profile_pic
                    ];
                    $owner_driver_id = $item->driver_id;
                } else
                    $owner_driver_id = 0;

                if ($item->forSaleDrivers) {
                    foreach ($item->forSaleDrivers as $forsale) {
                        if ($forsale->buyable_id == $item->driver_id) {
                            $owner_driver_id = $item->store->driver_id;
//                            array_push($status_trading, 'purchased');
                            $status_trading[] = 'purchased';
                        }
                        if ($forsale->buyable_id == NULL)
//                            array_push($status_trading, 'for_sale');
                            $status_trading[] = 'for_sale';

                        if ($forsale->seller_type == 'App/Driver')
                            $seller_type = 'driver';
                        else
                            $seller_type = 'company';
                    }

                }
                /**get exchange transfers
                 */
                if ($item->exchange) {
                    array_push($status_trading, 'for_exchange');
                }
                //End buy and sell
                if (empty($item->transferable->logo))
                    $client_img = $default_img;
                else
                    $client_img = $item->transferable->logo['thumb'];

                if (empty($status_trading))
                    array_push($status_trading, 'mine');

                $array = [
                    'id' => $item->id,
                    'status' => $item->status,
                    'number_of_booking' => $item->number_of_booking,
                    'owner_driver_id' => $owner_driver_id,
                    'driver_acceptance' => $item->driver_acceptance,
                    'type' => $item->type,
                    'start_date' => $item->transfer_start_time,
                    'end_date' => $item->transfer_end_time,
                    'notes' => $item->notes,
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
                        ],
                        'logo' => $client_img,
                    ],
                    'flight' => [
                        'flight_number' => $item->flight_number,
                        'departure_time' => $item->flight_departure_time,
                        'arrival_time' => $item->flight_arrival_time
                    ],
                    'car' => $cars,
                    'guests' => $guest_ar,
                    'driver' => $driver,
                    'trading_status' => $status_trading,
                    'seller_type' => $seller_type,
                ];

                /**get exchange transfers
                 */
                if ($item->exchange) {

                    if ($item->exchange->exchangeable_type == 'App\Driver')
                        $exchangeable_type = 'driver';
                    else
                        $exchangeable_type = 'company';
                    $offer_ar = [];
                    $att_ar = [];

                    foreach ($item->exchange->attributes as $attribute) {
                        $arr = ['id' => $attribute->id,
                            'from_date' => $attribute->from_date,
                            'to_date' => $attribute->to_date,
                            'airport' => $attribute->airport->name,
                            'type' => $attribute->type];
                        array_push($att_ar, $arr);
                    }

                    foreach ($item->exchange->offers as $transfer) {
                        $arr = ['id' => $transfer->id];
                        array_push($offer_ar, $arr);
                    }
                    $array['exchange'] = [
                        'id' => $item->exchange->id,
                        'exchange_type' => $exchangeable_type,
                        'attributes' => $att_ar,
                        'offers' => $offer_ar
                    ];
                }
                if ($item->type == 'arrival') {
                    if (empty($item->host->employer->profile_pic))
                        $host_profile_pic = $default_img;
                    else
                        $host_profile_pic = url('uploads/hosts/' . $item->host->employer->profile_pic);

                    if ($item->host_id)
                        $array['host'] = ['id' => $item->host->id,
                            'first_name' => $item->host->employer->first_name,
                            'last_name' => $item->host->employer->last_name,
                            'phone' => $item->host->employer->phone,
                            'gender' => $item->host->employer->gender,
                            'image' => $host_profile_pic
                        ];

                } else
                    $array['host'] = NULL;

                array_push($data, $array);
            }

            $d = [
                'current_page' => $page,
                'data' => $data,
                'per_page' => 10,
                'count' => count($data),

            ];
            return $this->sendResults($d);

        } elseif ($employer->type == 'host') {
            if ($request->has('company_id')) {
                $query->where('company_id', $request->company_id);
            }
            $query->where('host_id', $auth_id);
            $query->where('host_status', '!=', 'Guest Delivered');
            $query->where('status', '!=', 'End');
            $orderBy = 'transfer_start_time';
            $orderType = 'ASC';
            if ($request->has($orderBy)) {
                $orderBy = $request->orderby;
            }
            if ($request->has('ordertype')) {
                $orderType = $request->ordertype;
            }
            if ($request->has('type')) {
                $query->where('type', $request->type);
            }

            if ($request->has('from') && $request->has('to')) {
                $from = $request->from;
                $to = $request->to;
                $query->whereBetween('transfer_start_time', [$from, $to]);
            } else
                $query->DateFilter();

            $query->open()->orderBy($orderBy, $orderType);
            if (!$request->page)
                $page = 1;
            else
                $page = (int)$request->page;

            $result = $query->get();
            $arr = $result->forPage($request->page, 10);
            $data = [];
            foreach ($arr as $item) {
                $default_img = asset('assets/img/no-image-available.jpg');
                if (empty($item->airport->arrival_image['thumb']))
                    $airport_arrival_img = $default_img;
                else
                    $airport_arrival_img = $item->airport->arrival_image['thumb'];

                if (empty($item->airport->departure_image['thumb']))
                    $airport_departure_img = $default_img;
                else
                    $airport_departure_img = $item->airport->departure_image['thumb'];

                //guests
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

                //cars
                $cars = NULL;
                if ($item->car != NULL) {
                    $cars = [
                        'id' => $item->car->id,
                        'name' => $item->car->name,
                        'plate_number' => $item->car->plate_number,
                        'color' => $item->car->color,
                        'licence_plate' => $item->car->licence_plate,
                        'brand' => $item->car->brand,
                        'manufacture_year' => $item->car->manufacture_year,
                        'model' => [
                            'id' => $item->car_model->id,
                            'name' => $item->car_model->model_name,
                            'description' => $item->car_model->model_description,
                            'bags' => $item->car_model->max_bags,
                            'seats' => $item->car_model->max_seats,
                            'original' => $item->car_model->image['original']
                        ]
                    ];
                }
                // driver profile pic
                if (empty($item->driver->employer->profile_pic))
                    $driver_profile_pic = $default_img;
                else
                    $driver_profile_pic = url('uploads/drivers/' . $item->driver->employer->profile_pic);

                $driver = NULL;
                if ($item->driver_id) {
                    $driver = [
                        'id' => $item->driver->id,
                        'first_name' => $item->driver->employer->first_name,
                        'last_name' => $item->driver->employer->last_name,
                        'phone' => $item->driver->employer->phone,
                        'gender' => $item->driver->employer->gender,
                        'image' => $driver_profile_pic
                    ];
                    $owner_driver_id = $item->driver_id;
                } else
                    $owner_driver_id = 0;

                $array = [
                    'id' => $item->id,
                    'status' => $item->status,
                    'owner_driver_id' => $owner_driver_id,
                    'driver_acceptance' => $item->driver_acceptance,
                    'type' => $item->type,
                    'start_date' => $item->transfer_start_time,
                    'end_date' => $item->transfer_end_time,
                    'notes' => $item->notes,
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
                    'guests' => $guest_ar,
                    'driver' => $driver,
                    'trading_status' => ['mine']

                ];
                if (empty($item->host->employer->profile_pic))
                    $host_profile_pic = $default_img;
                else
                    $host_profile_pic = url('uploads/hosts/' . $item->host->employer->profile_pic);

                if ($item->host_id)
                    $array['host'] = ['id' => $item->host->id,
                        'first_name' => $item->host->employer->first_name,
                        'last_name' => $item->host->employer->last_name,
                        'phone' => $item->host->employer->phone,
                        'gender' => $item->host->employer->gender,
                        'image' => $host_profile_pic
                    ];


                array_push($data, $array);
            }

            $d = [
                'current_page' => $page,
                'data' => $data,
                'per_page' => 10,
                'count' => count($data),

            ];
            return $this->sendResults($d);

        }

    }

    private function transferQuery(Request $request)
    {
        $my_transfers = $this->getDriverTransfers($request);
        $exchange_transfers = $this->getExchangeTransfers($request);
        $for_sale = collect();
        if (!$request->purchased || $request->for_sale)
            $for_sale = $this->getForSaleTransfers($request);

        if ($request->ordertype == 'DESC') {
            $transfers = $my_transfers->merge($for_sale)->merge($exchange_transfers)
                ->sortByDesc('transfer_start_time');
        } else {
            $transfers = $my_transfers->merge($for_sale)->merge($exchange_transfers)
                ->sortBy('transfer_start_time');

        }


        return $transfers;
    }

    private function getDriverTransfers(Request $request)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $employer = Employer::findOrFail($auth_id);
        $query = Transfer:: with('car_model', 'car')
            ->where('request_status', 1)
            ->whereNull('deleted_at');
        if (($request->purchased && !$request->for_sale) || ($request->for_sale && !$request->purchased)) {
            $query->whereHas('forSaleDrivers', function ($q) use ($auth_id, $request) {
                if ($request->purchased && !$request->for_sale)
                    $q->where(['buyable_id' => $auth_id, 'buyable_type' => 'App\Driver']);
                if ($request->for_sale && !$request->purchased)
                    $q->whereNull('buyable_id');

            });
        }
        if ($request->purchased && $request->for_sale) {

            $query->whereHas('forSaleDrivers', function ($q) use ($auth_id, $request) {
                $q->where(['buyable_id' => $auth_id, 'buyable_type' => 'App\Driver'])
                    ->orWhereNull('buyable_id');
            });
        }
        if (!$request->purchased && !$request->for_sale) {
            $query->with(['forSaleDrivers' => function ($q) use ($auth_id) {
                $q->whereNull('buyable_id')
                    ->orWhere(['buyable_id' => $auth_id, 'buyable_type' => 'App\Driver']);
            }]);

        }
        $query->where('driver_id', $auth_id)->where('status', '!=', 'End');
        $orderBy = 'transfer_start_time';
        $orderType = 'ASC';
        if ($request->has($orderBy)) {
            $orderBy = $request->orderby;
        }
        if ($request->has('ordertype')) {
            $orderType = $request->ordertype;
        }
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        if ($request->has('from') && $request->has('to')) {
            $from = $request->from;
            $to = $request->to;
            $query->whereBetween('transfer_start_time', [$from, $to]);
        } else
            $query->DateFilter();

        $query->open()->orderBy($orderBy, $orderType);
        $my_transfer = $query->get();
        return $my_transfer;
    }

    private function getForSaleTransfers(Request $request)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $employer = Employer::findOrFail($auth_id);
        if ($request->has('from') && $request->has('to')) {
            $yesterday = $request->from;
            $tomorrow = $request->to;
        } else {
            $yesterday = date("Y-m-d", time() - 86400);
            $tomorrow = date("Y-m-d", time() + 86400);
        }

        $car_models = DB::table('car_models')
//            ->select('car_models.id,shifts.shift_start_time')
            ->join('car_car_model', 'car_car_model.car_model_id', '=', 'car_models.id')
            ->join('cars', 'cars.id', '=', 'car_car_model.car_id')
            ->join('shifts', 'shifts.car_id', '=', 'cars.id')
            ->where('shifts.driver_id', $employer->id)
//            ->whereDate('shifts.shift_start_time', '>=', $yesterday)
//            ->whereDate('shifts.shift_start_time', '<=', $tomorrow)
            ->whereNull('car_models.deleted_at')
            ->groupBy('car_models.id')
            ->pluck('car_models.id')->toArray();


        $query_for_sale = Transfer::whereHas('forSaleDrivers', function ($q) use ($auth_id) {
            $q->whereNull('buyable_id');
        })->with('car_model', 'car')
            ->where('request_status', 1)
            ->whereNull('deleted_at');
        $query_for_sale->where(function ($query) use ($auth_id) {
            $query->where('driver_id', '!=', $auth_id)
                ->orWhereNull('driver_id');
        });
        $query_for_sale->whereIn('car_model_id', $car_models);
        $query_for_sale->where('status', 'Pending');
        $orderBy = 'transfer_start_time';
        $orderType = 'ASC';
        if ($request->has($orderBy)) {
            $orderBy = $request->orderby;
        }
        if ($request->has('ordertype')) {
            $orderType = $request->ordertype;
        }
        if ($request->has('type')) {
            $query_for_sale->where('type', $request->type);
        }
        if ($request->has('from') && $request->has('to')) {
            $from = $request->from;
            $to = $request->to;
            $query_for_sale->whereBetween('transfer_start_time', [$from, $to]);
        } else
            $query_for_sale->DateFilter();
        $query_for_sale->open()->orderBy($orderBy, $orderType);

        $for_sale = $query_for_sale->get();

        return $for_sale;

    }

    private function getExchangeTransfers(Request $request)
    {
        $transfer_offered_for_exchange = Transfer::whereHas('exchange', function ($q) {
            $q->whereNull('offer_id');
        });
        $orderBy = 'transfer_start_time';
        $orderType = 'ASC';
        if ($request->has($orderBy)) {
            $orderBy = $request->orderby;
        }
        if ($request->has('ordertype')) {
            $orderType = $request->ordertype;
        }
        if ($request->has('type')) {
            $transfer_offered_for_exchange->where('type', $request->type);
        }
        if ($request->has('from') && $request->has('to')) {
            $from = $request->from;
            $to = $request->to;
            $transfer_offered_for_exchange->whereBetween('transfer_start_time', [$from, $to]);
        } else
            $transfer_offered_for_exchange->DateFilter();

        $transfer_offered_for_exchange->with('airport', 'transferable',
            'car_model', 'company', 'exchange')
            ->where('status', 'pending')->whereNull('deleted_at')
            ->orderBy('transfer_start_time', 'DESC');
        $result = $transfer_offered_for_exchange->open()->orderBy($orderBy, $orderType)->get();

        return $result;
    }

    public function history(Request $request)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $employer = Employer::findOrFail($auth_id);

        $query = Transfer:: with('car_model', 'car')
            ->where('request_status', 1)
            ->whereNull('deleted_at');
        $orderBy = 'transfer_start_time';
        $orderType = 'ASC';
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('date')) {
            $query->whereDate('transfer_start_time', $request->date);
        }
        if ($request->has('from') && $request->has('to')) {
            $from = $request->from;
            $to = $request->to;
            $query->whereBetween('transfer_start_time', [$from, $to]);
        }

        if ($employer->type == "driver") {
            $query->where('driver_id', $auth_id);
            $query->where('status', 'End');
            if ($request->has($orderBy)) {
                $orderBy = $request->orderby;
            }
            if ($request->has('ordertype')) {
                $orderType = $request->ordertype;
            }
            $query->orderBy($orderBy, $orderType);
            $data = [];
            if (!$request->page)
                $page = 1;
            else
                $page = (int)$request->page;

            $arr = $query->paginate();

            foreach ($arr as $item) {
                $default_img = asset('assets/img/no-image-available.jpg');
                if (empty($item->airport->arrival_image['thumb']))
                    $airport_arrival_img = $default_img;
                else
                    $airport_arrival_img = $item->airport->arrival_image['thumb'];

                if (empty($item->airport->departure_image['thumb']))
                    $airport_departure_img = $default_img;
                else
                    $airport_departure_img = $item->airport->departure_image['thumb'];

                //guests
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

                //cars
                $cars = NULL;
                if ($item->car != NULL) {
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
                // driver profile pic
                if (empty($item->driver->employer->profile_pic))
                    $driver_profile_pic = $default_img;
                else
                    $driver_profile_pic = url('uploads/drivers/' . $item->driver->employer->profile_pic);


                // Buy and sell
                $status_trading = [];
                $seller_type = '';
                $exchangeable_type = '';
                $driver = NULL;
                if ($item->driver_id) {
                    $driver = [
                        'id' => $item->driver->id,
                        'first_name' => $item->driver->employer->first_name,
                        'last_name' => $item->driver->employer->last_name,
                        'phone' => $item->driver->employer->phone,
                        'gender' => $item->driver->employer->gender,
                        'image' => $driver_profile_pic
                    ];
                    $owner_driver_id = $item->driver_id;
                } else
                    $owner_driver_id = 0;

                if ($item->forSaleDrivers) {
                    foreach ($item->forSaleDrivers as $forsale) {
                        if ($forsale->buyable_id == $item->driver_id) {
                            $owner_driver_id = $item->store->driver_id;
                            array_push($status_trading, 'purchased');
//                            $status_trading = 'purchased';
                        }
                        if ($forsale->buyable_id == NULL)
                            array_push($status_trading, 'for_sale');
//                            $status_trading = 'for_sale';

                        if ($forsale->seller_type == 'App/Driver')
                            $seller_type = 'driver';
                        else
                            $seller_type = 'company';
                    }

                }
                /**get exchange transfer
                 */
                if ($item->exchange) {
                    array_push($status_trading, 'for_exchange');
                    if ($item->exchange->exchangeable_type == 'App\Driver')
                        $exchangeable_type = 'driver';
                    else
                        $exchangeable_type = 'company';
                }
                //End buy and sell
                if (empty($item->transferable->logo))
                    $client_img = $default_img;
                else
                    $client_img = $item->transferable->logo['thumb'];

                if (empty($status_trading))
                    array_push($status_trading, 'mine');

                $array = [
                    'id' => $item->id,
                    'status' => $item->status,
                    'number_of_booking' => $item->number_of_booking,
                    'owner_driver_id' => $owner_driver_id,
                    'driver_acceptance' => $item->driver_acceptance,
                    'type' => $item->type,
                    'start_date' => $item->transfer_start_time,
                    'end_date' => $item->transfer_end_time,
                    'notes' => $item->notes,
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
                        ],
                        'logo' => $client_img,
                    ],
                    'flight' => [
                        'flight_number' => $item->flight_number,
                        'departure_time' => $item->flight_departure_time,
                        'arrival_time' => $item->flight_arrival_time
                    ],
                    'car' => $cars,
                    'guests' => $guest_ar,
                    'driver' => $driver,
                    'trading_status' => $status_trading,
                    'seller_type' => $seller_type,
                    'exchange_type' => $exchangeable_type,
                    'exchange_id' => ($item->exchange ? $item->exchange->id : 0)
                ];


                if ($item->type == 'arrival') {
                    if (empty($item->host->employer->profile_pic))
                        $host_profile_pic = $default_img;
                    else
                        $host_profile_pic = url('uploads/hosts/' . $item->host->employer->profile_pic);

                    if ($item->host_id)
                        $array['host'] = ['id' => $item->host->id,
                            'first_name' => $item->host->employer->first_name,
                            'last_name' => $item->host->employer->last_name,
                            'phone' => $item->host->employer->phone,
                            'gender' => $item->host->employer->gender,
                            'image' => $host_profile_pic
                        ];

                } else
                    $array['host'] = NULL;

                array_push($data, $array);
            }

            $d = [
                'current_page' => $page,
                'data' => $data,
                'per_page' => 10,
                'count' => count($data),

            ];
            return $this->sendResults($d);

        } elseif ($employer->type == 'host') {
            if ($request->has('company_id')) {
                $query->where('company_id', $request->company_id);
            }
            $query->where('host_id', $auth_id);
            $query->where('host_status', 'Guest Delivered');

            if ($request->has($orderBy)) {
                $orderBy = $request->orderby;
            }
            if ($request->has('ordertype')) {
                $orderType = $request->ordertype;
            }
            $query->orderBy($orderBy, $orderType);
            if (!$request->page)
                $page = 1;
            else
                $page = (int)$request->page;

            $result = $query->get();
            $arr = $result->forPage($request->page, 10);
            $data = [];
            foreach ($arr as $item) {
                $default_img = asset('assets/img/no-image-available.jpg');
                if (empty($item->airport->arrival_image['thumb']))
                    $airport_arrival_img = $default_img;
                else
                    $airport_arrival_img = $item->airport->arrival_image['thumb'];

                if (empty($item->airport->departure_image['thumb']))
                    $airport_departure_img = $default_img;
                else
                    $airport_departure_img = $item->airport->departure_image['thumb'];

                //guests
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

                //cars
                $cars = NULL;
                if ($item->car != NULL) {
                    $cars = [
                        'id' => $item->car->id,
                        'name' => $item->car->name,
                        'plate_number' => $item->car->plate_number,
                        'color' => $item->car->color,
                        'licence_plate' => $item->car->licence_plate,
                        'brand' => $item->car->brand,
                        'manufacture_year' => $item->car->manufacture_year,
                        'model' => [
                            'id' => $item->car_model->id,
                            'name' => $item->car_model->model_name,
                            'description' => $item->car_model->model_description,
                            'bags' => $item->car_model->max_bags,
                            'seats' => $item->car_model->max_seats,
                            'original' => $item->car_model->image['original']
                        ]
                    ];
                }
                // driver profile pic
                if (empty($item->driver->employer->profile_pic))
                    $driver_profile_pic = $default_img;
                else
                    $driver_profile_pic = url('uploads/drivers/' . $item->driver->employer->profile_pic);

                $driver = NULL;
                if ($item->driver_id) {
                    $driver = [
                        'id' => $item->driver->id,
                        'first_name' => $item->driver->employer->first_name,
                        'last_name' => $item->driver->employer->last_name,
                        'phone' => $item->driver->employer->phone,
                        'gender' => $item->driver->employer->gender,
                        'image' => $driver_profile_pic
                    ];
                    $owner_driver_id = $item->driver_id;
                } else
                    $owner_driver_id = 0;

                $array = [
                    'id' => $item->id,
                    'status' => $item->status,
                    'owner_driver_id' => $owner_driver_id,
                    'driver_acceptance' => $item->driver_acceptance,
                    'type' => $item->type,
                    'start_date' => $item->transfer_start_time,
                    'end_date' => $item->transfer_end_time,
                    'notes' => $item->notes,
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
                    'guests' => $guest_ar,
                    'driver' => $driver,
                    'trading_status' => ['mine']

                ];
                if (empty($item->host->employer->profile_pic))
                    $host_profile_pic = $default_img;
                else
                    $host_profile_pic = url('uploads/hosts/' . $item->host->employer->profile_pic);

                if ($item->host_id)
                    $array['host'] = ['id' => $item->host->id,
                        'first_name' => $item->host->employer->first_name,
                        'last_name' => $item->host->employer->last_name,
                        'phone' => $item->host->employer->phone,
                        'gender' => $item->host->employer->gender,
                        'image' => $host_profile_pic
                    ];


                array_push($data, $array);
            }

            $d = [
                'current_page' => $page,
                'data' => $data,
                'per_page' => 10,
                'count' => count($data),

            ];
            return $this->sendResults($d);

        }
    }

    public function show($id)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $employer = Employer::findOrFail($auth_id);
        $item = Transfer:: with('car_model', 'car', 'forSaleDrivers')
            ->where('request_status', 1)
            ->whereNull('deleted_at')
            ->findOrFail($id);

        $default_img = asset('assets/img/no-image-available.jpg');
        if (empty($item->airport->arrival_image['thumb']))
            $airport_arrival_img = $default_img;
        else
            $airport_arrival_img = $item->airport->arrival_image['thumb'];

        if (empty($item->airport->departure_image['thumb']))
            $airport_departure_img = $default_img;
        else
            $airport_departure_img = $item->airport->departure_image['thumb'];

        //guests
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
        //cars
        $cars = NULL;
        if ($item->car != NULL) {
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
        // driver profile pic
        if (empty($item->driver->employer->profile_pic))
            $driver_profile_pic = $default_img;
        else
            $driver_profile_pic = url('uploads/drivers/' . $item->driver->employer->profile_pic);

        // Buy and sell
        $status_trading = 'mine';
        $seller_type = '';
        $driver = [];
        if ($item->driver_id) {
            $driver = [
                'id' => $item->driver->id,
                'first_name' => $item->driver->employer->first_name,
                'last_name' => $item->driver->employer->last_name,
                'phone' => $item->driver->employer->phone,
                'gender' => $item->driver->employer->gender,
                'image' => $driver_profile_pic
            ];
            $owner_driver_id = $item->driver_id;
        } else
            $owner_driver_id = 0;
        if ($employer->type == 'driver') {
            if ($item->forSaleDrivers) {
                foreach ($item->forSaleDrivers as $forsale) {
                    if ($forsale->buyable_id == $item->driver_id) {
                        $owner_driver_id = $item->store->driver_id;
                        $status_trading = 'purchased';
                    }
                    if ($forsale->buyable_id == NULL)
                        $status_trading = 'for_sale';

                    if ($forsale->seller_type == 'App/Driver')
                        $seller_type = 'driver';
                    else
                        $seller_type = 'company';
                }

            }
        }
        //End buy and sell
        //
        $array = [
            'id' => $item->id,
            'status' => $item->status,
            'number_of_booking' => $item->number_of_booking,
            'owner_driver_id' => $owner_driver_id,
            'driver_acceptance' => $item->driver_acceptance,
            'type' => $item->type,
            'start_date' => $item->transfer_start_time,
            'end_date' => $item->transfer_end_time,
            'notes' => $item->notes,
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
            'guests' => $guest_ar,
            'driver' => $driver,
            'trading_status' => $status_trading,
            'seller_type' => $seller_type

        ];
        if ($item->type == 'arrival') {
            if (empty($item->host->employer->profile_pic))
                $host_profile_pic = $default_img;
            else
                $host_profile_pic = url('uploads/hosts/' . $item->host->employer->profile_pic);

            if ($item->host_id)
                $array['host'] = ['id' => $item->host->id,
                    'first_name' => $item->host->employer->first_name,
                    'last_name' => $item->host->employer->last_name,
                    'phone' => $item->host->employer->phone,
                    'gender' => $item->host->employer->gender,
                    'image' => $host_profile_pic
                ];

        } else
            $array['host'] = NULL;
        return $this->sendResults($array);
    }

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'datetime' => 'required',
            'car_model_id' => 'required',
            'flight_number' => 'required',
            'airport_id' => 'required',
            'hotel_id' => 'required',
            'type' => 'required',
            'price' => 'required',
            'number_of_booking' => 'required',
            'guests' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $car_model = Car_model::find($request->car_model_id);
        $hotel = Client::find($request->hotel_id);
        $airport = Airport::find($request->airport_id);

        $auth_id = Auth::guard('api')->user()->id;
        $employer = Employer::findOrFail($auth_id);
        $company = Company::findOrFail($employer->driver->company_id);
        $data = [
            'airport_id' => $request->airport_id,
            'type' => $request->type,
            'transfer_start_time' => $request->datetime,
            'flight_number' => $request->flight_number,
            'payment_type_id' => 1,
            'company_id' => $employer->driver->company_id,
            'driver_id' => $employer->id,
            'car_model_id' => $request->car_model_id,
            'request_status' => 1,
            'price' => $request->price,
            'number_seats' => $car_model->max_seats,
            'number_of_booking' => $request->number_of_booking,
            'driver_acceptance' => '1',
            'notes' => $request->notes

        ];
        if ($request->type == 'arrival') {
            $data['address_starting_point'] = $airport->address;
            $data['GPS_starting_point'] = $airport->lat . '-' . $airport->lang;
            $data['address_destination'] = $hotel->address;
            $data['GPS_destination'] = $hotel->lat . '-' . $hotel->lang;
        } else {
            $data['address_starting_point'] = $hotel->address;
            $data['GPS_starting_point'] = $hotel->lat . '-' . $hotel->lang;;
            $data['address_destination'] = $airport->address;
            $data['GPS_destination'] = $airport->lat . '-' . $airport->lang;;
        }
        if ($request->shift) {
            $shift = Shift::findOrFail($request->shift);
            $data['shift_id'] = $request->shift;
            $data['car_id'] = $shift->car->id;
        } else {
            $car_model_id = $request->car_model_id;
            $date = $request->datetime;
            $car = Car::whereHas('carModel', function ($query) use ($car_model_id) {
                $query->where('car_model_id', $car_model_id);
            })->whereNull('deleted_at')
                ->where('company_id', $employer->driver->company_id)
                ->first();
            if (!$car) {
                return $this->sendResults(['msg' => 'Please add a Car']);
            } else {
                $shift_inputs = ['shift_start_time' => $date,
                    'shift_end_time' => $date,
                    'car_id' => $car->id,
                    'driver_id' => $employer->id,
                    'company_id' => $employer->driver->company_id,
                ];
                $shift = Shift::create($shift_inputs);
                $data['shift_id'] = $shift->id;
                $data['car_id'] = $car->id;
            }
        }
        if ($request->type == 'arrival') {
            $host = Host::whereHas('companies', function ($q) use ($employer) {
                $q->where('company_id', $employer->driver->company_id);
            })->whereNull('deleted_at')->first();
            if ($host)
                $data['host_id'] = $host->id;
            else
                return $this->sendResults(['msg' => 'Please add host for airport ' . $airport->name]);
        } else
            $data['host_id'] = null;

        $transfer = new Transfer($data);
        $transfer->transferable()->associate($hotel)->save();
        if (!is_string($request->guests)) {
            return $this->sendResults(['msg' => 'Please make sure guests is string']);

        }
        $request->guests = json_decode($request->guests);
        foreach ($request->guests as $guest) {
            $identity_number = $guest->identity_number;
            $str = Guest::makeIdentityNumber($identity_number);
            $d =
                [
                    'company_id' => $employer->driver->company_id,
                    'client_id' => $hotel->id,
                    'driver_id' => $auth_id,
                    'nationality' => $guest->nationality,
                    'gender' => $guest->gender,
                    'phone' => $guest->phone,
                    'first_name' => $guest->first_name,
                    'last_name' => $guest->last_name,
                ];

            $guest_row = Guest::updateOrCreate(
                ['identity_number' => $str],
                $d
            );
            $transfer->guests()->attach($guest_row->id, [
                'host_id' => $data['host_id'],
                'room_number' => (isset($guest->room_number) ? $guest->room_number : null)
            ]);

        }

        return $this->sendResults(['transfer_id' => $transfer->id]);
    }

    public function cancel($id, Request $request, PushApi $pushApi, Notify $notify)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $validator = Validator::make($request->all(), [
            'cancel_reason' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $transfer = Transfer::findOrFail($id);

        $employer = Employer::findOrFail($auth_id);

        if ($employer->type == 'driver' && $transfer->shift->driver_id == $employer->id) {
            $cancel_date = \Carbon\Carbon::now();
            $update = $transfer->update([
                'cancelled' => 1,
                'cancel_reason' => $request->cancel_reason,
                'cancellation_date' => $cancel_date
            ]);
            $transfer->cancellable()->associate($employer)->save();
            //send PN to admins
            //$corporate_id = $employer->companies()->first()->id;
            $company_id = $employer->company_id;
            $company = Company::findOrFail($company_id);
            $admins = $company->Admins()->whereNull('deleted_at')->get();
            $notification = [
                'message' => 'Driver Cancel transfer',
                'title' => 'Driver Cancel transfer',
                'transfer_id' => $transfer->id,
                'id' => 10
            ];
            foreach ($admins as $admin) {
                if ($admin->device) {
                    $pushApi->sendAndroidPush($admin->device, $notification);
                }
            }
            $notify->NotifyAdmin($admins, $notification);
            //PN to host
            if ($transfer->host_id) {
                $this->notifyHost($notification, $transfer->host_id);
            }
            return response()->json(['status' => true]);
        } else {

            return $this->sendFailureResponse('You do not have permission for this');
        }

    }

    public function notifyHost($notification, $host_id)
    {
        $host = Employer::findOrFail($host_id);
        if ($host->device) {
            $pushApi = new PushApi();
            $pushApi->sendAndroidPush($host->device, $notification);
        }

    }

    public function start($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $transfer = Transfer::findOrFail($id);
        $auth_id = Auth::guard('api')->user()->id;
        $employer = Employer::findOrFail($auth_id);
        $this->checkForDuplicatedStatus($id, 'Start');
        $status = Status::create([
            'status_time' => \Carbon\Carbon::now(),
            'status' => 'Start',
            'lat' => $request->lat,
            'lang' => $request->lang
        ]);

        $status->statusable()->associate($transfer)->save();
        $status->actors()->associate($employer)->save();
        if ($transfer->host_id) {
            $notification = [
                'message' => 'Transfer started',
                'title' => 'Transfer started',
                'transfer_id' => $transfer->id,
                'transfer_start_time' => $transfer->transfer_start_time,
                'id' => 8
            ];
            $this->notifyHost($notification, $transfer->host_id);
        }
        $transfer->update(['status' => 'Start']);

        // delete from offer for sale
        Store::where('transfer_id', $id)->whereNull('buyable_id')->delete();
        //
        return response()->json(['status' => true]);
    }

    private function checkForDuplicatedStatus($transfer_id, $status)
    {
        $transfer = Transfer::findOrFail($transfer_id);
        if (!$transfer->statuses->where('status', $status)->isEmpty()) {
            if ($status == 'Start')
                $transfer->statuses()->delete();
            else
                $transfer->statuses->where('status', $status)->first()->delete();
        }

    }

    public function end($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lang' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $transfer = Transfer::findOrFail($id);
        $auth_id = Auth::guard('api')->user()->id;
        $employer = Employer::findOrFail($auth_id);
        $this->checkForDuplicatedStatus($id, 'End');
        $status = Status::create([
            'status_time' => \Carbon\Carbon::now(),
            'status' => 'End',
            'lat' => $request->lat,
            'lang' => $request->lang
        ]);
        $status->statusable()->associate($transfer)->save();
        $status->actors()->associate($employer)->save();
        $transfer->update(['status' => 'End']);

        return response()->json(['status' => true]);
    }

    public function driverAcceptance($id, PushApi $pushApi, Notify $notify)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $driver = Driver::findOrFail($auth_id);
        $transfer = Transfer::findOrFail($id);
        if ($transfer->shift->driver_id == $driver->id) {
            $transfer->update(['driver_acceptance' => '1']);
            //send PN to admins
            $company_id = $driver->company_id;
            $company = Company::findOrFail($company_id);
            $admins = $company->admins()->whereNull('deleted_at')->get();
            $notification = [
                'message' => 'Driver Accept transfer',
                'title' => 'Driver Acceptance',
                'transfer_id' => $transfer->id,
                'id' => 2
            ];
            foreach ($admins as $admin) {
                if ($admin->device) {
                    $pushApi->sendAndroidPush($admin->device, $notification);
                }
            }
            $notify->NotifyAdmin($admins, $notification);
            //
            return response()->json(['status' => true]);
        } else
            return response()->json(['status' => false], 401);

    }

    public function callDriver($id, PushApi $pushApi)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $host = Employer::where('type', 'host')->findOrFail($auth_id);
        $transfer = Transfer::whereNull('deleted_at')->findOrFail($id);
        if ($host->id == $transfer->host_id) {
            $driver_id = $transfer->shift->driver_id;
            $notification = [
                'message' => 'please Come Quickly',
                'title' => 'please Come Quickly',
                'transfer_start_time' => $transfer->transfer_start_time,
                'transfer_id' => $transfer->id,
                'id' => 4
            ];
            $this->notifyDriver($notification, $driver_id);
            //update transfer
//            $transfer_updates = ['host_status' => 'Call Driver'];
            if ($transfer->status != 'Start' || $transfer->status != 'End')
                $transfer_updates['status'] = 'Call Driver';
            $transfer->update($transfer_updates);
            $this->checkForDuplicatedStatus($id, 'call driver');
            //save in my log
            $status = Status::create([
                'status_time' => \Carbon\Carbon::now(),
                'status' => 'call driver',
            ]);
            $status->statusable()->associate($transfer)->save();
            $status->actors()->associate($host)->save();
            //
            return response()->json(['status' => true]);
        } else
            return $this->sendNotAcceptableResponse();

    }

    public function notifyDriver($notification, $driver_id)
    {
        $driver = Employer::findOrFail($driver_id);
        if ($driver->device) {
            $pushApi = new PushApi();
            $pushApi->sendAndroidPush($driver->device, $notification);
        }

    }

    public function guestReceived($id, PushApi $pushApi)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $host = Employer::where('type', 'host')->findOrFail($auth_id);
        $transfer = Transfer::whereNull('deleted_at')->findOrFail($id);
        if ($host->id == $transfer->host_id) {
            $driver_id = $transfer->shift->driver_id;
            $notification = [
                'message' => 'guest received',
                'title' => 'guest received',
                'transfer_start_time' => $transfer->transfer_start_time,
                'transfer_id' => $transfer->id,
                'id' => 5
            ];
            $this->notifyDriver($notification, $driver_id);
            //update in transfer
            $transfer_updates = ['host_status' => 'Guest Received'];
            if ($transfer->status != 'Start' || $transfer->status != 'End')
                $transfer_updates['status'] = 'Guest Received';
            $transfer->update($transfer_updates);
            $this->checkForDuplicatedStatus($id, 'Guest Received');
            //save in my log
            $status = Status::create([
                'status_time' => \Carbon\Carbon::now(),
                'status' => 'guest received',
            ]);
            $status->statusable()->associate($transfer)->save();
            $status->actors()->associate($host)->save();
            //
            return response()->json(['status' => true]);
        } else
            return $this->sendNotAcceptableResponse();
    }

    public function guestDelivered($id, PushApi $pushApi)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $host = Employer::where('type', 'host')->findOrFail($auth_id);
        $transfer = Transfer::whereNull('deleted_at')->findOrFail($id);
        if ($host->id == $transfer->host_id) {
            $driver_id = $transfer->shift->driver_id;
            $driver = Employer::findOrFail($driver_id);
            //update status in transfer
            $transfer_updates = ['host_status' => 'Guest Delivered'];
            if ($transfer->status != 'Start' || $transfer->status != 'End')
                $transfer_updates['status'] = 'Guest Delivered';
            $transfer->update($transfer_updates);
            $this->checkForDuplicatedStatus($id, 'guest delivered');
            //save in my log
            $status = Status::create([
                'status_time' => \Carbon\Carbon::now(),
                'status' => 'guest delivered',
            ]);
            $status->statusable()->associate($transfer)->save();
            $status->actors()->associate($host)->save();
            //
            return response()->json(['status' => true]);
        } else
            return $this->sendNotAcceptableResponse();
    }

    public function driverReplied($id, PushApi $pushApi)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $driver = Employer::where('type', 'driver')->findOrFail($auth_id);
        $transfer = Transfer::whereNull('deleted_at')->findOrFail($id);
        if ($driver->id == $transfer->driver->id) {
            $notification = [
                'message' => 'I am on my way',
                'title' => 'I am on my way',
                'transfer_id' => $transfer->id,
                'transfer_start_time' => $transfer->transfer_start_time,
                'id' => 7
            ];
            if ($transfer->host_id)
                $this->notifyHost($notification, $transfer->host_id);
            $this->checkForDuplicatedStatus($id, 'driver replied');
            //save in my log
            $status = Status::create([
                'status_time' => \Carbon\Carbon::now(),
                'status' => 'driver replied',
            ]);
            $status->statusable()->associate($transfer)->save();
            $status->actors()->associate($driver)->save();
            //
            return response()->json(['status' => true]);
        } else
            return $this->sendNotAcceptableResponse();
    }

}
