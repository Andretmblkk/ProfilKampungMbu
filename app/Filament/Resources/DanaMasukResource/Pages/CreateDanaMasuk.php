<?php

namespace App\Filament\Resources\DanaMasukResource\Pages;

use App\Filament\Resources\DanaMasukResource;
use Filament\Resources\Pages\CreateRecord;

class CreateDanaMasuk extends CreateRecord
{
    protected static string $resource = DanaMasukResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();

        return $data;
    }
}
