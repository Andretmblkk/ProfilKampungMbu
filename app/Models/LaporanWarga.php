<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanWarga extends Model
{
    protected $fillable = [
        'nomor_tiket',
        'nama_pelapor',
        'kontak',
        'kategori',
        'isi_laporan',
        'lampiran_path',
        'status',
        'tanggapan_admin',
    ];
}
