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
        Schema::create('pengeluarans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_anggaran_id')->nullable()->constrained('kategori_anggarans')->nullOnDelete();
            $table->foreignId('proyek_kampung_id')->nullable()->constrained('proyek_kampungs')->nullOnDelete();
            $table->string('kode_transaksi')->unique();
            $table->string('uraian');
            $table->decimal('nominal', 16, 2);
            $table->date('tanggal');
            $table->string('penerima')->nullable();
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
        Schema::dropIfExists('pengeluarans');
    }
};
