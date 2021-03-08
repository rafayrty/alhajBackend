<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $guarded = [];
     protected $with = ['vehicles','drivers','collectors'];
    use HasFactory;



    // In the model:
     public function getCreatedAtDiffAttribute(){

    return $this->created_at->diffForHumans();

     }
         public function vehicles(){

            return $this->belongsToMany('App\Models\Vehicles', 'vehicle_order');

         }

         public function drivers(){

            return $this->belongsToMany('App\Models\User', 'driver_order');

         }


         public function collectors(){

        return $this->belongsToMany('App\Models\User', 'collector_order');

        }
     /**
     * Get the orders record associated with the user.
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User','created_by');
    }
    public function modifier()
    {
        return $this->belongsTo('App\Models\User','last_modified');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function vehicle(){
        return $this->belongsTo('App\Models\Vehicles');
    }
}
