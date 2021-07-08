<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employer extends Authenticatable
{
    protected $fillable = [
        'first_name', 'last_name', 'phone',
        'username', 'password', 'email', 'type', 'api_token',
        'birth_date', 'gender', 'profile_pic', 'platform', 'locale',
        'last_login_at', 'last_login_ip', 'status','deleted_at'];


    public function device()
    {
        return $this->morphOne('App\Device', 'deviceable');
    }

    public function host()
    {
        return $this->hasOne('App\Host','id');
    }
    public function driver()
    {
        return $this->hasOne('App\Driver','id');
    }
}

