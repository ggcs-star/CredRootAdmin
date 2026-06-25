<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile',
        'status',
        'current_step',
    ];

    protected $hidden = [
        'password',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }
    public function sessions()
    {
        return $this->hasMany(DeviceSession::class, 'user_id');
    }
}