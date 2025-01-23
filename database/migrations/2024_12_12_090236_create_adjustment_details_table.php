<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('adjustment_details', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('adjustment_id'); // Reference to adjustments
            $table->unsignedBigInteger('product_id'); // Reference to products
            $table->integer('quantity'); // Adjustment amount (+/- value)
            $table->string('reason')->nullable(); // Reason for adjustment
            $table->timestamps(); // Created_at and updated_at

            // Foreign key constraints
            $table->foreign('adjustment_id')->references('id')->on('adjustments')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjustment_details');
    }
};




