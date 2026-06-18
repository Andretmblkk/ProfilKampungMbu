<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DanaMasuk extends Model
{
    protected $fillable = [
        'kategori_anggaran_id',
        'kode_transaksi',
        'sumber_dana',
        'nominal',
        'tanggal',
        'keterangan',
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
}
