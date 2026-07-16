<?php

namespace App\Filament\Resources\BeritaResource\Pages;

use App\Filament\Resources\BeritaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBerita extends EditRecord
{
    protected static string $resource = BeritaResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['published_at'] = $data['status'] === 'terbit'
            ? ($data['published_at'] ?? now())
            : null;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
