<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFleetsTable extends Migration
{
    public function up()
    {
        Schema::create('fleets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->string('category');
            $table->text('description');
            $table->string('image');
            $table->integer('position');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fleets');
    }
}
