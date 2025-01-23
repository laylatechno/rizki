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
        Schema::create('adjustments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('document_number')->unique(); // Unique document number
            $table->date('adjustment_date'); // Date of adjustment
            $table->unsignedBigInteger('user_id'); // User who made the adjustment
            $table->text('note')->nullable(); // Optional notes
            $table->timestamps(); // Created_at and updated_at

            // Foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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





