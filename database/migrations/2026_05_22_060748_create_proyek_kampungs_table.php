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
        Schema::create('proyek_kampungs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_anggaran_id')->nullable()->constrained('kategori_anggarans')->nullOnDelete();
            $table->string('nama');
            $table->string('slug')->unique();
            $table->string('lokasi');
            $table->text('deskripsi')->nullable();
            $table->decimal('anggaran', 16, 2);
            $table->decimal('realisasi', 16, 2)->default(0);
            $table->unsignedTinyInteger('progress')->default(0);
            $table->string('status')->default('direncanakan');
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->string('foto_path')->nullable();
            $table->string('dokumen_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyek_kampungs');
    }
};
