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
        Schema::create('dana_masuks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_anggaran_id')->nullable()->constrained('kategori_anggarans')->nullOnDelete();
            $table->string('kode_transaksi')->unique();
            $table->string('sumber_dana');
            $table->decimal('nominal', 16, 2);
            $table->date('tanggal');
            $table->text('keterangan')->nullable();
            $table->string('bukti_path')->nullable();
            $table->string('status')->default('menunggu');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dana_masuks');
    }
};
