<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

class RestController extends Controller
{
    /**
     * RestController constructor.
     * @param Request $request
     */
    function __construct(Request $request)
    {
        //get locale from header

        if ($request->hasHeader('Content-Language')) {
            $locale = $request->header('Content-Language');
            app()->setLocale($locale);
        }

    }

    protected function sendInternalError()
    {
        return response()->json(['msg' => 'Internal server error.'], 500);
    }

    /*
     * send results paginated
     */
    protected function sendPaginatedResults($query, Request $request)
    {
        $per_page = 5; //default pagination per page

        if ($request->has('limit')) {
            $per_page = $request->limit;
        }
//        else{
//            $per_page = $query->count();
//        }
        return response()->json($query->paginate($per_page)->appends($request->except('page')), 200);

    }

    protected function paginateResult($query, Request $request)
    {
        $per_page = 10; //default pagination per page

        if ($request->has('limit')) {
            $per_page = $request->limit;
        } else {
            $per_page = $query->count();
        }
        return $query->paginate($per_page)->appends($request->except('page'));
    }

    public function paginate($items, $perPage = 1, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }

    /* send result with out pagination */
    protected function sendResults($data)
    {
        return response()->json($data, 200);
    }

    /*send created respons*/
    protected function sendCreatedResponse($data = null)
    {
        if ($data) {
            return response()->json($data, 201);
        }
        return response()->json(['msg' => 'created successfully'], 201);
    }

    /*send success response*/
    protected function sendSuccessResponse($msg = null)
    {
        if (!$msg) {
            $msg = 'successfully operated';
        }
        return response()->json(['msg' => $msg], 200);
    }

    /*send failure response*/
    protected function sendFailureResponse($msg = null)
    {
        if (!$msg) {
            $msg = 'failed to operate';
        }
        return response()->json(['msg' => $msg], 400);
    }

    /*send failure response*/
    protected function sendNotAcceptableResponse($msg = null)
    {
        if (!$msg) {
            $msg = 'not acceptable';
        }
        return response()->json(['msg' => $msg], 406);
    }

    /*send success response*/
    protected function sendForbiddensResponse($msg = null)
    {
        if (!$msg) {
            $msg = 'forbidden';
        }
        return response()->json(['msg' => $msg], 403);
    }

    /*send not found response*/
    protected function sendNotFoundResponse()
    {
        return response()->json(['msg' => __('messages.not_found')], 404);
    }

    /*send validation errors*/
    protected function sendValidationErrors($errors = [])
    {
        return response()->json($errors, 422);
    }

    /*send wrong username credenticals*/
    protected function sendWrongCredentials()
    {
        return response()->json(['msg' => 'wrong email or password.'], 403);
    }

    /*convert base64 to image*/
    protected function save_base64_image($base64_image_string, $output_file_without_extentnion, $path_with_end_slash = "")
    {
        //usage:  if( substr( $img_src, 0, 5 ) === "data:" ) {  $filename=save_base64_image($base64_image_string, $output_file_without_extentnion, getcwd() . "/application/assets/pins/$user_id/"); }
        //
        //data is like:    data:image/png;base64,asdfasdfasdf
        $splited = explode(',', substr($base64_image_string, 5), 2);
        if (count($splited) < 2) {
            $output_file_with_extentnion = $output_file_without_extentnion . '.png';
            $data = $base64_image_string;
        } else {
            $mime = $splited[0];
            $data = $splited[1];

            $mime_split_without_base64 = explode(';', $mime, 2);
            $mime_split = explode('/', $mime_split_without_base64[0], 2);
            if (count($mime_split) == 2) {
                $extension = $mime_split[1];
                if ($extension == 'jpeg') $extension = 'jpg';
                //if($extension=='javascript')$extension='js';
                //if($extension=='text')$extension='txt';
                $output_file_with_extentnion = $output_file_without_extentnion . '.' . $extension;
            }
        }


        file_put_contents($path_with_end_slash . $output_file_with_extentnion, base64_decode($data));

        //generate thumb
        \Image::make($path_with_end_slash . $output_file_with_extentnion)->resize(200, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save($path_with_end_slash . 'thumbs/' . $output_file_with_extentnion);

        return $output_file_with_extentnion;
    }

    protected function transferObject($transfers)
    {

        $data = [];
        foreach ($transfers as $item) {
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
            /**get exchange transfers
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

        return $data;
    }
}
