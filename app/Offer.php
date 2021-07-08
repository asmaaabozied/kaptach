<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = ['exchange_id', 'company_id', 'transfer_id', 'offerable_id', 'offerable_type', 'status'];

    protected $with = ['transfer'];

    public function exchange()
    {
        return $this->belongsTo('App\Exchange');
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function offerable()
    {
        return $this->morphTo();
    }

    public function transfer()
    {
        return $this->belongsTo('App\Transfer');
    }
}
