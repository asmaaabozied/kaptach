<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Airport extends Model
{
    protected $fillable = ['name', 'station_id', 'lat', 'lang', 'address','arrival_image','departure_image'];

    public function station()
    {
        return $this->belongsTo('App\Station');
    }

    public function getArrivalImageAttribute($value)
    {
        if ($value) {
            return [
                'original' => url('uploads/airports/' . $value),
                'thumb' => url('uploads/airports/thumbs/' . $value)
            ];
        }
        return $value;
    }
    public function getDepartureImageAttribute($value)
    {
        if ($value) {
            return [
                'original' => url('uploads/airports/' . $value),
                'thumb' => url('uploads/airports/thumbs/' . $value)
            ];
        }
        return $value;
    }
    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function shuttles()
    {
        return $this->hasMany('App\Shuttle');
    }

    public function hosts()
    {
        return $this->hasMany('App\Host');
    }
}
