<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = ['identity_number', 'first_name', 'last_name', 'phone', 'gender', 'company_id',
        'client_id','driver_id', 'nationality'];


    public static function makeIdentityNumber($string)
    {
        $record = Guest::where('identity_number', $string)
            ->latest('id')->first();
        if ($record) {
            $string = increment_string($record->identity_number);
            $string = self::makeIdentityNumber($string);
        }

        return $string;
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver');
    }

    public function transfers()
    {
        return $this->belongsToMany('App\Transfer', 'guest_transfer')
            ->withPivot('room_number')
            ->withTimestamps();
    }
}

