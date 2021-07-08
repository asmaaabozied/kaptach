<?php

namespace App\Http\Controllers\Api\V2;

use App\Admin;
use App\Helpers\Utilities;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Hash;

class AdminAuthController extends Controller
{
    public $successStatus = 200;
    public function login(Request $request, Utilities $utility)
    {
        $validator = Validator::make($request->all(),
            [
                'username' => 'required',
                'password' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $admin = Admin::where('username', $request->username)
            ->whereNull('deleted_at')->first();
        if (Hash::check($request->password, $admin->password)) {
            $default_img = asset('assets/img/no-image-available.jpg');
            if (empty($admin->image))
                $admin_img = $default_img;
            else
                $admin_img = url('/uploads/admins/thumbs/' . $admin->image);

            //update api token
            $token = $utility->randomCode();
            $platform = $request->platform;
            $updated_data = [
                'api_token' => $token,
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => $request->getClientIp(),
            ];
            $admin->update($updated_data);

            //send json data
            $success['user'] = [
                'id' => $admin->id,
                'username' => $admin->username,
                'email' => $admin->email,
                'phone' => $admin->phone,
                'gender' => $admin->gender,
                'type' => $admin->type,
                'locale' => $admin->locale,
                'status' => $admin->status,
                'image' => $admin_img,
            ];
            $success['accessToken'] = $admin->api_token;
            return response()->json($success, $this->successStatus);
        }
        return response()->json(['error' => 'Invalid username or password'], 400);
    }
}
