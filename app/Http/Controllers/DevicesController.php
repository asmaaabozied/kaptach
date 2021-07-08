<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Helpers\PushApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DevicesController extends BaseController
{

    public function store(Request $request, PushApi $pushApi)
    {

        $uid = Auth::guard('admin')->user()->id;
        $admin = Admin::FindOrFail($uid);
        $this->validate($request,
            [
                'token' => 'required',
                'platform'=>'required',
            ]);
        if ($admin->device) {
            $pushApi->deleteDevice($admin->device->token);
        }
        $pushApi->addDevice($admin, $request->token, $request->platform);
        return response()->json(['status' => true]);
    }
}
