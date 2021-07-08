<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tour_price_list extends Model
{
    protected $fillable = ['company_id', 'car_model_id', 'tourism_place', 'with_food',
        'number_hours', 'tours_start_time', 'tours_end_time', 'price'];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    public function carModel()
    {
        return $this->belongsTo('App\Car_model');
    }
}
