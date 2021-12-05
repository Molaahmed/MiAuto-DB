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
        $table->string('type');
        $table->string('fuel');
	    $table->string('make');
        $table->string('model');
        $table->string('engine');
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
