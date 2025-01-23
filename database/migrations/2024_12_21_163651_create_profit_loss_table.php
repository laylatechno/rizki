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
        Schema::create('profit_loss', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_id')->constrained('cash')->onDelete('cascade');
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('cascade'); // Ganti relasi ke tabel transactions
            $table->foreignId('order_id')->nullable()->constrained('orders')->onDelete('cascade'); // Relasi opsional ke orders
            $table->foreignId('purchase_id')->nullable()->constrained('purchases')->onDelete('cascade'); // Relasi opsional ke purchases
            $table->date('date');  // Ganti nama kolom menjadi 'date'
            $table->bigInteger('amount');
            $table->timestamps();
        });
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profit_loss');
    }
};
