<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the orders record associated with the user.
     */
    public function vehicles(){

        return $this->belongsToMany('App\Models\Orders', 'vehicles_order');

     }

     public function drivers(){

        return $this->belongsToMany('App\Models\Orders', 'driver_order');

     }


     public function collectors(){

    return $this->belongsToMany('App\Models\Orders', 'collector_order');

    }
    public function roles(){
        return $this->belongsToMany('App\Models\Role', 'role_user');
    }



    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'image',
        'login_id',
        'status',
        'fcm'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
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
}
