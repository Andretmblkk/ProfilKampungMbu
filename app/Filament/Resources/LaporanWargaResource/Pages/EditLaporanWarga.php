<?php

namespace App\Filament\Resources\LaporanWargaResource\Pages;

use App\Filament\Resources\LaporanWargaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLaporanWarga extends EditRecord
{
    protected static string $resource = LaporanWargaResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
