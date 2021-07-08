<?php

namespace App\Http\Controllers\Api;

use App\Car;
use App\Driver;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarsController extends Controller
{
    public function atLeastOneCarIsExist($model_id)
    {
        $auth_id = Auth::guard('api')->user()->id;
        $driver = Driver::findOrFail($auth_id);
        $car = Car::whereHas('carModel', function ($query) use ($model_id) {
            $query->where('car_model_id', $model_id);
        })->whereNull('deleted_at')
            ->where('company_id', $driver->company_id)
            ->first();
        if ($car) {
            return response()->json(['found' => true]);
        } else {
            return response()->json(['found' => false]);
        }
    }
}
