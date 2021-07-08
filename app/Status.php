<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $fillable = ['actors_id','actors_type','statusable_id','statusable_type','status','status_time',
        'lat','lang'

    ];
    public function statusable()
    {
        return $this->morphTo();
    }
    public function actors()
    {
        return $this->morphTo();
    }
    /**status string [guest received,call driver,driver replied,guest delivered,start,end]***/
}
