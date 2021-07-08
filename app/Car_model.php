<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car_model extends Model
{
    protected $fillable = ['model_name', 'model_description', 'max_seats',
        'max_bags', 'image'];

    public function getModelWithSeatsAttribute()
    {
        return $this->model_name . '( Seats: ' . $this->max_seats . ' Bags:' . $this->max_bags . ' )';
    }

    /**
     * add full url to image attribute
     * @param $value
     * @return array
     */
    public function getImageAttribute($value)
    {
        if ($value) {
            return [
                'original' => url('uploads/car_models/' . $value),
                'thumb' => url('uploads/car_models/thumbs/' . $value)
            ];
        }
        return $value;
    }

    public function cars()
    {
        return $this->belongsToMany('App\Car');
    }

    //Transfers requested Car By Model
    public function transfers()
    {
        return $this->hasMany('App\Transfer');

    }
}
