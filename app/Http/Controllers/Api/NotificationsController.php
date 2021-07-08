<?php

namespace App\Http\Controllers\Api;

use App\Device;
use App\Employer;
use App\Helpers\PushApi;
use App\Http\Controllers\Controller;
use App\Http\Controllers\RestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationsController extends RestController
{
    public function index(Request $request)
    {
        $auth = Auth::guard('api')->user()->id;
        $employer=Employer::findOrFail($auth);
        if($employer->notifications){

        }

    }

    public function send(Request $request, PushApi $pushApi)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'message' => 'required',
            'title' => 'required',
            'token' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendResults([
                'error' => 'ValidationError',
                'errors' => $validator->errors()
            ], 400);
        }
        $device = Device::where('token', $request->token)->first();
        if ($device) {
            $arr = ['message' => $request->message,
                'title' => $request->title];
            if ($request->id)
                $arr['id'] = $request->id;
            if ($request->transfer_id)
                $arr['transfer_id'] = $request->transfer_id;
            $pushApi->sendAndroidPush($device, $arr);
        } else
            return $this->sendResults([
                'result' => 'fail'
            ]);
        return $this->sendResults([
            'result' => 'success'
        ]);
    }
}
