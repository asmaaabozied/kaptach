<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['exchange_id', 'from_date', 'to_date', 'airport_id', 'type'];

    public function transfer()
    {
        return $this->belongsTo('App\Transfer');
    }

    public function exchange()
    {
        return $this->belongsTo('App\Exchange');
    }

    public function airport()
    {
        return $this->belongsTo('App\Airport');
    }
}
