<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use HasRoles;
    use Notifiable;
    protected $guard_name = 'admin';
    protected $fillable = [
        'username', 'email', 'password', 'phone', 'image', 'status', 'type',
        'role_id', 'adminable_id', 'adminable_type','api_token','locale',
        'last_login_at', 'last_login_ip',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function device()
    {
        return $this->morphOne('App\Device', 'deviceable');
    }

    public function adminable()
    {
        return $this->morphTo();
    }

    public function transfers()
    {
        return $this->hasMany('App\Transfer');
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function roles(): MorphToMany
    {
        return $this->morphToMany('App\Role', 'model', 'model_roles', 'model_id', 'role_id');
    }

    public static function generateUsername($string)
    {
        $username = make_slug($string);
        $username = preg_replace('/-[0-9]*$/', '', $username);
        $record = Admin::where('username', 'REGEXP', $username . '?-?([0-9]*$)')
            ->latest('id')->first();
        if ($record)
            $username = increment_string($record->username);

        return $username;
    }
}
