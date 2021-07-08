<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['parent_id', 'name', 'slug', 'code', 'website', 'contact_phone',
        'contact_email', 'type', 'status', 'lat', 'lang', 'address', 'logo', 'receive_request_from_drivers'];

    public function admins()
    {
        return $this->morphMany('App\Admin', 'adminable');
    }

    public function station()
    {
        return $this->belongsTo('App\Station');
    }

    public function parent()
    {
        return $this->belongsTo(Company::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Company::class, 'parent_id');
    }

    public function scopeType($query)
    {
        return $query->where('type', 'hotel');
    }

    public function hosts()
    {
        return $this->belongsToMany('App\Host')->withPivot(['status'])
            ->withTimestamps();
    }

    public function drivers()
    {
        return $this->hasMany('App\Driver');
    }

    public function cancellations()
    {
        return $this->morphToMany('App\Transfer', 'cancellable');
    }

    public function buyers()
    {
        return $this->morphOne('App\Store', 'buyable');
    }

    public function actor()
    {
        return $this->morphOne('App\Status', 'actors', 'actors_type', 'actors_id');
    }

    public function shuttles()
    {
        return $this->belongsto('App\Company');

    }

    public function guests()
    {
        return $this->belongsToMany('App\Guest', 'employer_shuttle')
            ->withTimestamps();
    }

    public function payments()
    {
        return $this->hasMany('App\Payment');
    }

    public function cars()
    {
        return $this->hasMany('App\Car')->whereNull('deleted_at');
    }

    public static function makeSlug($string)
    {
        $slug = make_slug($string);
        $slug = preg_replace('/-[0-9]*$/', '', $slug);
//        $locale = locales()->where('code', $locale)->first();
        $record = Company::where('slug', 'REGEXP', $slug . '?-?([0-9]*$)')
            ->latest('id')->first();
        if ($record)
            $slug = increment_string($record->slug);

        return $slug;
    }
    public function exchange()
    {
        return $this->morphOne('App\Exchange','exchangeable');
    }
}
