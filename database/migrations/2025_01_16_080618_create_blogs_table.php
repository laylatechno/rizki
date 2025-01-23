<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->date('posting_date')->nullable();
            $table->string('writer')->nullable();
            $table->string('resume')->nullable();
            $table->text('description')->nullable();
            $table->string('reference')->nullable();
            $table->string('image')->nullable();
            $table->string('status')->nullable();
            $table->string('position')->nullable();
            $table->integer('views')->nullable();

            // Relasi dengan blog_categories
            $table->foreignId('blog_category_id')->constrained('blog_categories')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blogs');
    }
}
