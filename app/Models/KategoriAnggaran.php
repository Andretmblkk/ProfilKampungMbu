<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriAnggaran extends Model
{
    protected $fillable = [
        'nama',
        'slug',
        'warna',
        'ikon',
        'deskripsi',
        'pagu_anggaran',
    ];

    protected function casts(): array
    {
        return [
            'pagu_anggaran' => 'decimal:2',
        ];
    }

    public function danaMasuks(): HasMany
    {
        return $this->hasMany(DanaMasuk::class);
    }

    public function pengeluarans(): HasMany
    {
        return $this->hasMany(Pengeluaran::class);
    }

    public function proyekKampungs(): HasMany
    {
        return $this->hasMany(ProyekKampung::class);
    }
}
