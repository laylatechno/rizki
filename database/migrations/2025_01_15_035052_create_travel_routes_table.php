<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelRoutesTable extends Migration
{
    public function up()
    {
        Schema::create('travel_routes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('start');
            $table->string('end');
            $table->text('description');
            $table->string('image');
            $table->integer('position');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('travel_routes');
    }
}
