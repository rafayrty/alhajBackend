<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{   protected $fillable = [
    'name',
    'status',
    'image',
];

    use HasFactory;
      /**
     * Get the orders record associated with the user.
     */
    public function order(){
        return $this->hasOne('App\Models\Orders','order_id');

    }
    public function vehicles(){

        return $this->belongsToMany('App\Models\Orders', 'vehicles_order');

     }
}
