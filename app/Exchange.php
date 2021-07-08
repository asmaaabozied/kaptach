<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    protected $fillable = ['company_id','transfer_id', 'driver_id',
        'exchangeable_id', 'exchangeable_type','offer_id'];

    public function exchangeable()
    {
        return $this->morphTo();
    }
    public function attributes()
    {
        return $this->hasMany('App\Attribute');
    }
    public function transfer()
    {
        return $this->belongsTo('App\Transfer');
    }
    public function offers()
    {
        return $this->hasMany('App\Offer');
    }
}
