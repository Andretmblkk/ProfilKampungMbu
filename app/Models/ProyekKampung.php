<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected static function booted(): void
    {
        static::saving(function (ProyekKampung $project): void {
            $project->progress = max(0, min(100, (int) $project->progress));
            $project->status = match (true) {
                $project->progress === 0 => 'direncanakan',
                $project->progress === 100 => 'selesai',
                default => 'berjalan',
            };
        });
    }

    public function kategoriAnggaran(): BelongsTo
    {
        return $this->belongsTo(KategoriAnggaran::class);
    }

    public function pengeluarans(): HasMany
    {
        return $this->hasMany(Pengeluaran::class);
    }
}
