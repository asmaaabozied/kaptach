<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = ['deviceable_id', 'deviceble_type', 'token','platform'];

    public function deviceable()
    {
        return $this->morphTo();
    }
}
