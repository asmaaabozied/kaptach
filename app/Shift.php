<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = ['company_id', 'car_id', 'driver_id', 'shift_start_time', 'shift_end_time',
        'login_time', 'logout_time'];

    public function car()
    {
        return $this->belongsTo('App\Car');
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver');
    }
}
