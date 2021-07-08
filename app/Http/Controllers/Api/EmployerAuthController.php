<?php

namespace App\Http\Controllers\Api;

use App\Driver;
use App\Helpers\Files;
use App\Helpers\PushApi;
use App\Helpers\Utilities;
use App\Host;
use App\Jobs\ResizeImage;
use App\Mail\RestCodeMail;
use Carbon\Carbon;
use Hash;
use App\Employer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Validator;

class EmployerAuthController extends Controller
{
    public $successStatus = 200;

    //only for driver
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'username' => 'required|unique:employers,username|alpha_dash|max:50',
                'first_name' => 'required|max:50',
                'last_name' => 'required|max:50',
                'password' => 'required|min:6',
                'phone' => 'required|unique:employers,phone|regex:/(90)[0-9]{9}/',
                'email' => 'required|unique:employers,email|max:50',
                'gender' => 'required|in:male,female',
                'birth_date' => 'required|date|date_format:Y-m-d',
                'type' => 'required',
                'company_id' => 'required_if:type,commercial',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $inputs = $request->all();

        $inputs['type'] = 'driver';
        $inputs['password'] = bcrypt($inputs['password']);
        $inputs['company_id'] = $request->company_id;
        $driver_type = 'commercial';
        $inputs['status'] = 'pending';
        if ($request->type == 'personal') {
            $company = $this->curlApi($request);
            $inputs['company_id'] = $company->id;
            $driver_type = 'personal';
        }

        $employer = Employer::create($inputs);
        if ($employer) {
            Driver::create([
                'id' => $employer->id,
                'company_id' => $inputs['company_id'],
                'phone' => $request->phone,
                'gender' => $request->gender,
                'driver_type' => $driver_type
            ]);
        }
        $success['status'] = $inputs['status'];
        return response()->json($success, $this->successStatus);
    }

    public function login(Request $request, Utilities $utility, PushApi $pushApi)
    {
        $validator = Validator::make($request->all(),
            [
                'username' => 'required',
                'password' => 'required',
                'platform' => 'required'
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $employer = Employer::where('username', $request->username)
            ->whereNull('deleted_at')->first();

        if ($employer) {
            $check_status = $employer->status;
            if ($check_status == 'pending') {
                return response()->json(['status' => 'pending'], 200);
            } else if ($check_status == 'rejected') {
                return response()->json(['status' => 'rejected'], 200);
            } else {
                if (Hash::check($request->password, $employer->password)) {
                    $default_img = asset('assets/img/no-image-available.jpg');
                    if (empty($employer->profile_pic))
                        $img = $default_img;
                    else {
                        if ($employer->type == 'host')
                            $img = url('/uploads/hosts/thumbs/' . $employer->profile_pic);
                        else
                            $img = url('/uploads/drivers/thumbs/' . $employer->profile_pic);
                    }

                    //update api token
                    $token = $utility->randomCode();
                    $platform = $request->platform;
                    $updated_data = [
                        'api_token' => $token,
                        'platform' => $platform,
                        'last_login_at' => Carbon::now()->toDateTimeString(),
                        'last_login_ip' => $request->getClientIp(),
                    ];
                    $employer->update($updated_data);

                    //send json data
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
                    if ($employer->type == 'driver') {
                        $success['user']['driver_type'] = $employer->driver->driver_type;
                        $success['user']['company'][]= [
                            'id' => $employer->driver->company->id,
                            'name' => $employer->driver->company->name,
                            'type' => $employer->driver->company->type
                        ];
                    } else {
                        $success['user']['airport'] = $employer->host->airport->name;
                        $host = Host::with('companies')->findOrFail($employer->id);
                        $companies=[];
                        foreach ($host->companies as $company) {
                            $company = [
                                'id' => $company->id,
                                'name' => $company->name,
                                'type' => $company->type
                            ];
                            array_push($companies,$company);
                        }
                        $success['user']['company']=$companies;
                    }


                    $success['accessToken'] = $employer->api_token;
                    return response()->json($success, $this->successStatus);
                } else
                    return response()->json(['error' => 'Invalid username or password'], 400);
            }
        }
        return response()->json(['status' => 'Unauthorised'], 200);
    }

    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'email' => 'required|email',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $employer = Employer::where('email', $request->email)->first();
        if ($employer) {
            $reset_code = Str::random(5);
            $details = [
                'title' => 'Mail from kaptan-vip.com',
                'link' => route('password/reset', ['reset_code' => $reset_code]),
            ];
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'reset_code' => $reset_code, //change 60 to any length you want
                'active_till' => Carbon::now()->addDays(7),
                'created_at' => Carbon::now()
            ]);
            \Mail::to($request->email)->send(new RestCodeMail($details));

            return response()->json(['status' => true]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'reset_code' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $reset_code = $request->reset_code;
        return view('emails.views.reset_password', compact('reset_code'));
    }

    public function storePassword(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'new_password' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $result = DB::table('password_resets')->where('reset_code', $request->reset_code)
            ->where('active_till', '>', Carbon::now())->first();
        if ($result) {
            $employer = Employer::where('email', $result->email)->first();
            if ($employer) {
                $employer->update(['password' => bcrypt($request->new_password)]);
                DB::table('password_resets')->where('email', $result->email)->delete();
                return response()->json(['status' => true]);
            }
            return response()->json(['status' => false]);
        } else {
            return response()->json(['status' => false]);
        }
    }

    public function checkUsername(Request $request)
    {
        $validator = Validator::make($request->all(), ['username' => 'required']);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        if (!empty($request->accessToken)) {
            $employer = Employer::where('username', $request->username)
                ->where('api_token', '!=', $request->accessToken)->first();
        } else {
            $employer = Employer::where('username', $request->username)->first();
        }
        if ($employer) {
            return response()->json(['available' => false]);
        } else {
            return response()->json(['available' => true]);
        }


    }

    private function curlApi(Request $request)
    {
        $baseUri = "http://localhost:8080/kaptan/public/api/v1/companies/create";
        $headers = array(
            'Content-Type: application/json'
        );
        $fields = array(
            'username' => $request->username,
            'contact_phone' => $request->phone,
            'contact_email' => $request->email
        );
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $baseUri);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        // Close connection
        curl_close($ch);
        return json_decode($result);
    }


}
