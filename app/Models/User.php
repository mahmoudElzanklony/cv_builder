<?php

namespace App\Models;

use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable , SoftDeletes;

    protected $appends = ['full_name'];


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

    public function setSerialNumberAttribute()
    {
        $this->attributes['serial_number'] = Str::uuid();
    }

     public function getFullNameAttribute()
     {
         return $this->username.' ... 11';
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
        'bio',
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


    public function image()
    {
        return $this->morphOne(images::class,'imageable');
    }

    public function owner_cvs()
    {
        return $this->hasMany(templates::class,'user_id')->whereRaw('parent_id is null');
    }

    public function fork_cvs()
    {
        return $this->hasMany(templates::class,'user_id')->whereRaw('parent_id is not null');
    }





}
