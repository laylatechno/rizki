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
        Schema::create('stock_opname_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_opname_id')->constrained('stock_opname')->onDelete('cascade'); // Relasi ke tabel stock_opname
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Relasi ke tabel produk
            $table->integer('stock_system')->default(0); // Jumlah stok menurut sistem
            $table->integer('stock_real')->default(0); // Jumlah stok berdasarkan hasil opname
            $table->integer('difference')->default(0); // Selisih antara stok sistem dan fisik
            $table->text('description')->nullable(); // Catatan untuk perbedaan stok

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opname_detail');
    }
};
