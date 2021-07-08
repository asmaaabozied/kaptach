<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = ['name', 'status'];

    public function companies()
    {
        return $this->hasMany('App\Company');
    }
}
