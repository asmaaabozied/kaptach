<?php

namespace App\Http\Controllers\Api;

use App\Employer;
use App\Helpers\Files;
use App\Helpers\PushApi;
use App\Host;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RestController;
use App\Jobs\ResizeImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EmployersController extends RestController
{
    public $successStatus = 200;

    public function index(Request $request)
    {
        if ($request->employer_id) {
            $employer = Employer::find($request->employer_id);
        } else {
            $employer = Auth::guard('api')->user()->id;
            $employer = Employer::find($employer);
        }
        $default_img = asset('assets/img/no-image-available.jpg');
        if (empty($employer->profile_pic))
            $img = $default_img;
        else {
            if ($employer->type == 'host')
                $img = url('/uploads/hosts/thumbs/' . $employer->profile_pic);
            else
                $img = url('/uploads/drivers/thumbs/' . $employer->profile_pic);
        }

        $success['user'] = [
            'id' => $employer->id,
            'username' => $employer->username,
            'first_name' => $employer->first_name,
            'last_name' => $employer->last_name,
            'email' => $employer->email,
            'phone' => $employer->phone,
            'birth_date' => $employer->birth_date,
            'type' => $employer->type,
            'locale' => $employer->locale,
            'status' => $employer->status,
            'image' => $img,
        ];
        if ($employer->type == 'host') {
            $success['user']['airport'] = $employer->host->airport->name;
            $host = Host::with('companies')->findOrFail($employer->id);
            $companies = [];
            foreach ($host->companies as $company) {
                $company = [
                    'id' => $company->id,
                    'name' => $company->name,
                    'type' => $company->type
                ];
                array_push($companies, $company);
            }
            $success['user']['company'] = $companies;
        }

        if ($employer->type == 'driver') {
            $success['user']['driver_type'] = $employer->driver->driver_type;
            $success['user']['company'][] = [
                'id' => $employer->driver->company->id,
                'name' => $employer->driver->company->name,
                'type' => $employer->driver->company->type
            ];
        }


        $success['accessToken'] = $employer->api_token;
        return response()->json($success, $this->successStatus);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editProfile(Request $request)
    {
        $employer = Auth::guard('api')->user()->id;
        $employer = Employer::find($employer);
        if ($employer) {
            $validator = Validator::make($request->all(),
                [
                    'first_name' => 'max:50',
                    'last_name' => 'max:50',
                    'phone' => 'regex:/(90)[0-9]{9}/|unique:employers,phone,' . $employer->id,
                    'email' => 'max:50|unique:employers,email,' . $employer->id,
                    'gender' => 'in:male,female',
                    'birth_date' => 'date|date_format:Y-m-d',
                ]);
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()], 422);
            }
            if ($employer->update($request->all())) {
                $default_img = asset('assets/img/no-image-available.jpg');
                if (empty($employer->profile_pic))
                    $img = $default_img;
                else
                    $img = url('/uploads/drivers/thumbs/' . $employer->profile_pic);


                $success['user'] = [
                    'id' => $employer->id,
                    'username' => $employer->username,
                    'first_name' => $employer->first_name,
                    'last_name' => $employer->last_name,
                    'email' => $employer->email,
                    'phone' => $employer->phone,
                    'birth_date' => $employer->birth_date,
                    'type' => $employer->type,
                    'locale' => $employer->locale,
                    'status' => $employer->status,
                    'image' => $img,
                ];
                if ($employer->type == 'host') {
                    $success['user']['airport'] = $employer->host->airport->name;
                    $host = Host::with('companies')->findOrFail($employer->id);
                    $companies = [];
                    foreach ($host->companies as $company) {
                        $company = [
                            'id' => $company->id,
                            'name' => $company->name,
                            'type' => $company->type
                        ];
                        array_push($companies, $company);
                    }
                    $success['user']['company'] = $companies;
                }

                if ($employer->type == 'driver') {
                    $success['user']['driver_type'] = $employer->driver->driver_type;
                    $success['user']['company'][] = [
                        'id' => $employer->driver->company->id,
                        'name' => $employer->driver->company->name,
                        'type' => $employer->driver->company->type
                    ];
                }

                $success['accessToken'] = $employer->api_token;
                return response()->json($success, $this->successStatus);
            } else
                return response()->json(['status' => false]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    public function uploadProfileImage(Request $request, Files $files)
    {
        $validator = Validator::make($request->all(),
            [
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $employer = Auth::guard('api')->user()->id;
        $employer = Employer::find($employer);
        if ($request->hasFile('image')) {
            if ($employer->type == 'driver')
                $upload_folder = 'drivers';
            else
                $upload_folder = 'hosts';
            $image = $files->uploadAndResizeImage($request->image, 'uploads/' . $upload_folder, 200);
            $this->dispatch(new ResizeImage($upload_folder, $image));
            $employer->update(['profile_pic' => $image]);
            return response()->json(['image' => url('uploads/' . $upload_folder . '/thumbs/' . $image)], $this->successStatus);
        }

    }

    public function logout(PushApi $pushApi)
    {
        $employer = Employer::findOrFail(Auth::guard('api')->user()->id);
        $pushApi->deleteDevice($employer->device->token);
        $employer->update(['api_token' => NULL, 'platform' => NULL]);
        return $this->sendSuccessResponse('logged out successfully');
    }


}
