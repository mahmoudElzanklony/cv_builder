<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable , SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

     public function getUsernameAttribute($val)
     {
         return ucfirst($val).' ... 11';
     }

    public function setUsernameAttribute($value)
    {
        $this->attributes['username'] = ucfirst($value);
    }

    protected $fillable = [
        'username',
        'email',
        'password',
        'phone',
        'address',
        'image',
        'block',
        'role_id',
        'country_id',


    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role(){
        return $this->belongsTo(roles::class,'role_id');
    }

    public function country(){
        return $this->belongsTo(countries::class,'country_id');
    }

    public function listings(){
        return $this->hasMany(listings_info::class,'user_id');
    }

    public function user_info(){
        return $this->hasOne(user_info::class,'user_id');
    }


}
