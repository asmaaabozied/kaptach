<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['station_id','driver_id', 'company_id', 'name', 'slug', 'code', 'website',
        'contact_phone', 'contact_email', 'type', 'status', 'lat', 'lang', 'address', 'logo'];
    public function getLogoAttribute($value)
    {
        if ($value) {
            return [
                'original' => url('uploads/clients/' . $value),
                'thumb' => url('uploads/clients/thumbs/' . $value)
            ];
        }
        return $value;
    }
    public function admins()
    {
        return $this->morphMany('App\Admin', 'adminable');
    }

    public function station()
    {
        return $this->belongsTo('App\Station');
    }

    public function shuttles()
    {
        return $this->belongsToMany('App\Shuttle');
    }
    public function transfers()
    {
        return $this->morphMany('App\Transfer', 'transferable');
    }

    public static function makeSlug($string)
    {
        $slug = make_slug($string);
        $slug = preg_replace('/-[0-9]*$/', '', $slug);
//        $locale = locales()->where('code', $locale)->first();
        $record = Client::where('slug', 'REGEXP', $slug . '?-?([0-9]*$)')
            ->latest('id')->first();
        if ($record)
            $slug = increment_string($record->slug);

        return $slug;
    }

}
