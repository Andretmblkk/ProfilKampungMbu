<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AktivitasLog extends Model
{
    protected $fillable = [
        'user_id',
        'aksi',
        'modul',
        'deskripsi',
        'ip_address',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }
}
