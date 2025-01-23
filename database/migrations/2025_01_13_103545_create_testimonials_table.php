<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestimonialsTable extends Migration
{
    public function up()
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->text('description');
            $table->string('image');
            $table->string('position');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('testimonials');
    }
}
