<?php

namespace App\Filament\Resources\BeritaResource\Pages;

use App\Filament\Resources\BeritaResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBerita extends CreateRecord
{
    protected static string $resource = BeritaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['author_id'] = auth()->id();
        $data['published_at'] = $data['status'] === 'terbit'
            ? ($data['published_at'] ?? now())
            : null;

        return $data;
    }
}
