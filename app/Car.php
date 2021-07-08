<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    protected $fillable = ['company_id', 'driver_id', 'name', 'plate_number',
        'color', 'brand', 'licence_plate', 'manufacture_year','status'];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function carModel()
    {
        return $this->belongsToMany('App\Car_model');
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver');
    }
    public function shifts()
    {
        return $this->hasMany('App\Shift');
    }
    public function transfers()
    {
        return $this->hasMany('App\Transfer');
        
    }
    public function shuttles()
    {
        return $this->hasMany('App\Shuttle');
        
    }

}
