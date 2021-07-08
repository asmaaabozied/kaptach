<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer_price_list extends Model
{
    protected $fillable = ['client_id', 'airport_id', 'car_model_id', 'company_id', 'departure_price', 'arrival_price'];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function clients()
    {
        return $this->belongsTo('App\Client', 'client_id');
    }

    public function airport()
    {
        return $this->belongsTo('App\Airport');
    }

    public function carModel()
    {
        return $this->belongsTo('App\Car_model');
    }
}
