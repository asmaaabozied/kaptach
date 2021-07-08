<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shuttle extends Model
{
    protected $fillable = ['shift_id', 'company_id', 'airport_id', 'car_model_id', 'station_id', 'shuttle_start_time', 'shuttle_end_time', 'payment_type_id',
        'number_of_booking', 'number_seats', 'address_start_point', 'address_destination', 'GPS_starting_point', 'GPS_destination', 'type', 'price'];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function airport()
    {
        return $this->belongsTo('App\Airport');
    }

    public function car()
    {
        return $this->belongsTo('App\Car');
    }

    public function car_model()
    {
        return $this->belongsTo('App\Car_model');
    }

    public function station()
    {
        return $this->belongsTo('App\Station');
    }

    public function shift()
    {
        return $this->belongsTo('App\Shift', 'shift_id');
    }

//    public function employers()
//    {
//        return $this->belongsToMany('App\Employer', 'guest_shuttle')
//            ->withPivot('guest_id')
//            ->withTimestamps();
//    }

    public function guests()
    {
        return $this->belongsToMany('App\Guest', 'guest_shuttle')
            ->withTimestamps();
    }

    public function clients()
    {
        return $this->belongsToMany('App\Client', 'client_shuttle')
            ->withPivot('price')
            ->withTimestamps();
    }
    public function paymentType()
    {
        return $this->belongsTo('App\Payment_type');
    }

}
