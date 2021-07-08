<?php

namespace App\Http\Controllers\Api\V2;

use App\Admin;
use App\Employer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\PushApi;
class DevicesController extends Controller
{

    public function store(Request $request, PushApi $pushApi)
    {

        $uid = Auth::guard('api')->user()->id;
        $employer = Employer::FindOrFail($uid);
        $validator = Validator::make($request->all(),
            [
                'token' => 'required',
                'platform' => 'required|in:android'
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        if ($employer->device) {
            $pushApi->deleteDevice($employer->device->token);
        }
        $pushApi->addDevice($employer, $request->token, $request->platform);
        return response()->json(['status' => true]);
    }

    public function adminStore(Request $request, PushApi $pushApi)
    {
        $uid = Auth::guard('admin_api')->user()->id;
        $admin = Admin::FindOrFail($uid);
        $validator = Validator::make($request->all(),
            [
                'token' => 'required',
                'platform' => 'required|in:android'
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
        $platform_device=$admin->device->where('platform',$request->platform)->first();
        if ($platform_device) {
            $pushApi->deleteDevice($platform_device->token);
        }
        $pushApi->addDevice($admin, $request->token, $request->platform);
        return response()->json(['status' => true]);
    }

    public function destroy($token, PushApi $pushApi)
    {
        if ($token) {
            $pushApi->deleteDevice($token);
        }
        return response()->json(['status' => true]);
    }
}
