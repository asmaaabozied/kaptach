<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    protected $fillable = ['id', 'company_id', 'driving_licence_number', 'phone', 'gender', 'driver_type'];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function employer()
    {
        return $this->hasOne('App\Employer', 'id');
    }

    public function shifts()
    {
        return $this->hasMany('App\shift');
    }

    public function buyers()
    {
        return $this->morphOne('App\Store', 'buyable');
    }

    public function cancellations()
    {
        return $this->morphToMany('App\Transfer', 'cancellable');
    }

    public function actor()
    {
        return $this->morphOne('App\Status', 'actors', 'actor_type', 'actor_id');
    }

    public function transfers()
    {
        return $this->hasMany('App\Transfer');
    }

    public function notifications()
    {
        return $this->morphOne('App\Notification', 'notifiable');
    }

    public function exchange()
    {
        return $this->morphOne('App\Exchange','exchangeable');
    }


}
