<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProyekKampung extends Model
{
    protected $fillable = [
        'kategori_anggaran_id',
        'nama',
        'slug',
        'lokasi',
        'deskripsi',
        'anggaran',
        'realisasi',
        'progress',
        'status',
        'tanggal_mulai',
        'tanggal_selesai',
        'foto_path',
        'dokumen_path',
    ];

    protected function casts(): array
    {
        return [
            'anggaran' => 'decimal:2',
            'realisasi' => 'decimal:2',
            'progress' => 'integer',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
        ];
    }

    public function kategoriAnggaran(): BelongsTo
    {
        return $this->belongsTo(KategoriAnggaran::class);
    }
}
