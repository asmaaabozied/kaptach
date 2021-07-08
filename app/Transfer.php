<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Http\Traits\RecordsActivity;

class Transfer extends Model
{
    use RecordsActivity;

    protected $fillable = ['company_id', 'requested_by_admin', 'took_action_by_admin', 'airport_id',
        'car_id', 'car_model_id', 'flight_number', 'flight_date', 'number_seats', 'number_of_booking',
        'type', 'price', 'shift_id', 'driver_id', 'transferable_id', 'transferable_type', 'payment_type_id',
        'number_of_booking', 'flight_departure_time', 'address_starting_point', 'GPS_starting_point',
        'address_destination', 'GPS_destination', 'took_action_by_admin', 'transfer_start_time',
        'transfer_end_time', 'request_status', 'cancelled', 'cancellable_id', 'cancellable_type', 'host_id',
        'cancel_reason', 'cancellation_date', 'status', 'driver_acceptance', 'host_status', 'notes'];


    public function scopeOfYear($query, $year)
    {
        return $query->whereYear('transfer_start_time', $year);
    }

    public function scopeOpen($query)
    {
        return $query->where('cancelled', 0);
    }

    public function scopeStart($query)
    {
        return $query->where('status', '!=', 'end');
    }

    public function scopeClient($query, $id)
    {
        return $query->where('transferable_type', 'App\Client')->where('transferable_id', $id);
    }

    public function scopeDateFilter($query)
    {
        $tomorrow = date("Y-m-d", time() + 86400);
        $yesterday = date("Y-m-d", time() - 86400);
        return $query->whereDate('transfer_start_time', date('Y-m-d'))
            ->OrWhereDate('transfer_start_time', $tomorrow)
            ->OrWhereDate('transfer_start_time', $yesterday);
    }

    public function company()
    {
        return $this->belongsTo('App\Company');
    }

    public function host()
    {
        return $this->belongsTo('App\Host');
    }

    public function driver()
    {
        return $this->belongsTo('App\Driver');
    }

    public function guests()
    {
        return $this->belongsToMany('App\Guest', 'guest_transfer')
            ->withPivot('room_number')
            ->withTimestamps();
    }

    public function hosts()
    {
        return $this->belongsToMany('App\Host', 'guest_transfer')
            ->withTimestamps();
    }

    public function car()
    {
        return $this->belongsTo('App\Car');
    }

    public function car_model()
    {
        return $this->belongsTo('App\Car_model');
    }

    public function airport()
    {
        return $this->belongsTo('App\Airport');
    }

    public function transferable()
    {
        return $this->morphTo();
    }

    // For who be able to cancel transfer.

    public function cancellable()
    {
        return $this->morphTo();
    }

    public function statuses()
    {
        return $this->morphMany('App\Status', 'statusable');
    }

    public function shift()
    {
        return $this->belongsTo('App\Shift', 'shift_id');
    }

    public function requestedAdmin()
    {
        return $this->belongsTo('App\Admin', 'requested_by_admin');
    }

    public function actionByAdmin()
    {
        return $this->belongsTo('App\Admin', 'took_action_by_admin');
    }

    public function paymentType()
    {
        return $this->belongsTo('App\Payment_type');
    }

    public function store()
    {
        return $this->hasOne('App\Store');
    }

    public function forSaleDrivers()
    {
        return $this->hasMany('App\Store')
            ->where('type', 'sale')
            ->whereIn('store_for', ['1', '3']);
    }

    public function forSalesCompanies()
    {
        return $this->hasMany('App\Store')
            ->where('type', 'sale')
//            ->whereNull('buyable_id')
            ->whereIn('store_for', ['2', '3']);
    }

    public function MyTransferForSale()
    {
        return $this->hasone('App\Store')
            ->where('type', 'sale');
//            ->whereNull('buyable_id');
//            ->whereIn('store_for', ['2', '3']);
    }

    public function otherTransfersForSale()
    {
        return $this->hasone('App\Store')
            ->where('type', 'sale')
            ->whereNull('buyable_id')
            ->whereIn('store_for', ['2', '3']);
    }

    public function purchased()
    {
        return $this->hasone('App\Store')
            ->where('type', 'sale')
//            ->whereNull('buyable_id')
            ->whereIn('store_for', ['2', '3']);
    }

    public function exchange()
    {
        return $this->hasOne('App\Exchange');
    }
    public function offer()
    {
        return $this->hasOne('App\Offer');
    }
}

