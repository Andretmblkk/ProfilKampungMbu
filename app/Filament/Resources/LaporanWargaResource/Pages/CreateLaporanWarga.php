<?php

namespace App\Filament\Resources\LaporanWargaResource\Pages;

use App\Filament\Resources\LaporanWargaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateLaporanWarga extends CreateRecord
{
    protected static string $resource = LaporanWargaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['nomor_tiket'] ??= 'LWK-'.now()->format('Ymd').'-'.Str::upper(Str::random(5));

        return $data;
    }
}
