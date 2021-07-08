<?php

namespace App;

use App\Http\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use RecordsActivity;
    protected $fillable = ['transfer_id', 'company_id','driver_id' ,'type','seller_id','seller_type',
        'buyable_id', 'buyable_type', 'store_for'];

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function transfer()
    {
        return $this->belongsTo('App\Transfer');
    }

    public function buyable()
    {
        return $this->morphTo();
    }

    public function scopeDriver($query, $driver_id)
    {
        $query->whereNull('buyable_id')
            ->orWhere(['buyable_id' => $driver_id, 'buyable_type' => 'App\Driver'])
            ->orWhere('driver_id',$driver_id);
    }
}
