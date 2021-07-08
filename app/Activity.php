<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['subject_id', 'subject_type', 'name', 'user_id','description'];
    protected $with = ['subject'];
    /**
     * Get the user responsible for the given activity.
     *
     * @return User
     */
//    public function user()
//    {
//        return $this->belongsTo('App\Admin');
//    }

    /**
     * Get the subject of the activity.
     *
     * @return mixed
     */
    public function subject()
    {
        return $this->morphTo();
    }
}
