<?php

namespace App\Http\Controllers;

use App\Admin;
use App\Car;
use App\Car_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BaseController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            $admin_id = auth('admin')->user()->id;
            $admin = Admin::whereNull('deleted_at')->findOrFail($admin_id);
            $unreadNotifications = $admin->unreadNotifications;
            view()->share('unreadNotifications', $unreadNotifications);

            return $next($request);
        });
    }


    protected function driverShifts($request)
    {
        $date = $request['date'];
        $car_model_id = $request['car_model_id'];
        $cars = Car::whereHas('carModel', function ($query) use ($car_model_id) {
            $query->where('car_model_id', $car_model_id);
        })->whereNull('deleted_at')
            ->where('company_id', auth('admin')->user()->adminable->id)
            ->get();

        $str = '';
        foreach ($cars as $car) {
            if ($car->carModel) {
                $shifts = $car->shifts()->where('company_id', auth('admin')->user()->adminable->id)
                    ->where('shift_start_time', '<=', $date)
                    ->where('shift_end_time', '>', $date)->whereNull('deleted_at')->get();
                foreach ($shifts as $shift) {
                    $username = $shift->driver->employer->first_name . ' ' . $shift->driver->employer->last_name;
                    $start_time = date('H:i', strtotime($shift->shift_start_time));
                    $end_time = date('H:i', strtotime($shift->shift_end_time));
                    $checked = '';
                    if (isset($request['shift'])) {
                        if ($request['shift'] == $shift->id) {
                            $checked = 'checked';
                        }
                    }
                    $model = Car_model::find($car_model_id);
                    $max_seats = $model->max_seats;
                    $max_bags = $model->max_bags;
                    $str .= " <div class=\"radio\">
                            <label>
                                <input type=\"radio\"  name=\"shift\" $checked value=\"$shift->id\" >
                                $username
                               <p>$start_time - $end_time</p> 
                               <p>$car->plate_number</p> 
                               <i class=\"fa fa-male\"></i> x $max_seats
                               <i class=\"fa fa-suitcase\"></i> x  $max_bags
                            </label>
                        </div>";
                }
            }
        }
        return $str;
    }




}
