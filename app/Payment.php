<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('deletedAt', function (Builder $builder) {
            $builder->whereNull('deleted_at');
        });
    }
    protected $fillable = ['client_id', 'payment_type_id', 'amount', 'deducted_year',
        'deducted_month'];

    public function client()
    {
        return $this->belongsTo('App\Client');
    }

    public function paymentTypes()
    {
        return $this->belongsTo('App\Payment_type');
    }
}
