<?php

namespace App\Http\Controllers\Api;

use App\Car_model;
use App\Employer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarModelsController extends Controller
{
    public $successStatus = 200;

    public function index()
    {
        $auth_id = Auth::guard('api')->user()->id;
        $employer = Employer::findOrFail($auth_id);
        $car_models = Car_model::whereNull('deleted_at')->get();
        $arr=[];
        foreach ($car_models as $car_model)
        {
          $model=  ['id' => $car_model->id,
                'name' => $car_model->model_name,
                'description' => $car_model->model_description,
                'bags' => $car_model->max_bags,
                'seats' => $car_model->max_seats,
                'original' => $car_model->image['original']
            ];
          array_push($arr,$model);
        }
        $data['car_models'] = $arr;
        return response()->json($data, $this->successStatus);
    }
}
