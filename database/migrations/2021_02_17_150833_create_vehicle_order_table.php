<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehicleOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_order', function (Blueprint $table) {
            $table->bigInteger('vehicles_id')->unsigned();
            $table->bigInteger('orders_id')->unsigned();
          
        
        });
        Schema::table('vehicle_order', function($table) {
            $table->foreign('vehicles_id')->references('id')->on('vehicles')->onDelete('cascade');
            $table->foreign('orders_id')->references('id')->on('orders')->onDelete('cascade');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_order');
    }
}
