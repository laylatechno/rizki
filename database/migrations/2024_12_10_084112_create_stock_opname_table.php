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
        Schema::create('stock_opname', function (Blueprint $table) {
            $table->id();
            $table->string('no_stock_opname')->unique(); // Kode unik untuk stock opname
            $table->date('date')->nullable(); // Tanggal stock opname
            $table->text('description')->nullable(); // Keterangan atau catatan tambahan
            $table->enum('status', ['Pending', 'Selesai'])->default('Pending'); // Status stock opname

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opname');
    }
};
