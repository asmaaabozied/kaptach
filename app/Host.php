<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Host extends Model
{
    protected $fillable = ['id','airport_id', 'phone', 'gender'];

    public function companies()
    {
        return $this->belongsToMany('App\Company')->withPivot(['status'])
            ->withTimestamps();
    }
    public function employer()
    {
        return $this->hasOne('App\Employer', 'id');
    }

    public function airport()
    {
        return $this->belongsTo('App\Airport');
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

}
