<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shuttle_price_list extends Model
{

    protected $fillable = ['client_id', 'airport_id', 'company_id', 'departure_price', 'arrival_price'];

    public function company_id()
    {
        return $this->belongsTo('App\Company');
    }

    public function clients()
    {
        return $this->belongsTo('App\Client', 'client_id');
    }

    public function airports()
    {
        return $this->belongsTo('App\Airport', 'airport_id');
    }
}
