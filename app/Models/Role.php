<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
class Role extends Authenticatable
{
    public $timestamps = false;


    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'role_user');
    }
}


