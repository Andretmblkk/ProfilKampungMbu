<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pengeluaran extends Model
{
    protected $fillable = [
        'kategori_anggaran_id',
        'proyek_kampung_id',
        'kode_transaksi',
        'uraian',
        'nominal',
        'tanggal',
        'penerima',
        'bukti_path',
        'status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'nominal' => 'decimal:2',
        ];
    }

    public function kategoriAnggaran(): BelongsTo
    {
        return $this->belongsTo(KategoriAnggaran::class);
    }

    public function proyekKampung(): BelongsTo
    {
        return $this->belongsTo(ProyekKampung::class);
    }
}
