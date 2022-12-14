<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('order_code');
            $table->integer('id_costumer');
            $table->integer('id_car');
            $table->datetime('pickup_time');
            $table->integer('rental_time');
            $table->string('status');
            $table->datetime('start_rental')->nullable(); 
            $table->datetime('end_rental')->nullable(); 
            $table->integer('price')->nullable();
            $table->string('return_code')->nullable();
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
};
