<?php

namespace App\Models;

use App\Search\Searchable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable,Searchable;

    public $resourceName = "User";
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'phone',
        'address',
        'district_id',
        'city_id',
        'country_id',
        'role'
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

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function District()
    {
        return $this->belongsTo('App\Models\District','district_id','id');
    }
    public function City()
    {
        return $this->belongsTo('App\Models\City','city_id','id');
    }
    public function Country()
    {
        return $this->belongsTo('App\Models\Country','country_id','id');
    }
}
