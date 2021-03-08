<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('assigned_by');
            // $table->integer('collector_ids');
            // $table->integer('driver_ids');
            // $table->integer('vehicle_ids');
            $table->text('details')->nullable();
            $table->string('image')->nullable();
            $table->string('shipping');
            $table->string('client');
            $table->string('phone');
            $table->string('note')->nullable();
            $table->string('status');
            $table->string('payment');
            $table->string('recipient')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->string('date')->nullable();
            $table->string('lat');
            $table->string('lng');
            $table->tinyInteger('urgent')->default(0);
            $table->tinyInteger('certificate')->default(0);
            $table->integer('last_modified')->nullable();
            $table->integer('created_by');
            $table->string('receipt')->nullable();
            $table->timestamp('shipped', 0)->nullable();
            $table->timestamp('started', 0)->nullable();
            $table->timestamp('delivered', 0)->nullable();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
