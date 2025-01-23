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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_category_id')->constrained('transaction_categories')->onDelete('cascade'); // Relasi ke transaction_categories
            $table->foreignId('cash_id')->constrained('cash')->onDelete('cascade'); // Relasi ke tabel kas
            $table->string('name', 100); // Nama transaksi
            $table->decimal('amount', 15, 2); // Jumlah transaksi
            $table->date('date'); // Tanggal transaksi
            $table->text('description')->nullable(); // Deskripsi transaksi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
