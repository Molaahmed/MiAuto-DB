<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
        $table->bigIncrements('id');
        $table->unsignedBigInteger('client_id');
        $table->string('vin_number')->nullable();
        $table->string('plate')->nullable();
        $table->string('color')->nullable();;
        $table->boolean('air_conditioner')->nullable();;
        $table->string('type');
        $table->string('fuel');
	    $table->string('make');
        $table->string('engine');
        $table->string('model');
        $table->string('gear_box');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
