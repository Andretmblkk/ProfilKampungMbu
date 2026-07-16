<?php

namespace App\Filament\Resources\LaporanKeuanganResource\Pages;

use App\Filament\Resources\LaporanKeuanganResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLaporanKeuangan extends CreateRecord
{
    protected static string $resource = LaporanKeuanganResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['uploaded_by'] = auth()->id();
        $data['file_type'] = strtolower(pathinfo($data['file_path'], PATHINFO_EXTENSION) ?: 'pdf');

        return $data;
    }
}
