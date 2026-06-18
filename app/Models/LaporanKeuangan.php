<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKeuangan extends Model
{
    protected $fillable = [
        'judul',
        'kategori',
        'periode',
        'tanggal_laporan',
        'file_path',
        'file_type',
        'file_size',
        'status',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_laporan' => 'date',
        ];
    }
}
