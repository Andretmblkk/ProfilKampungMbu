<?php

namespace App\Filament\Resources\KategoriAnggaranResource\Pages;

use App\Filament\Resources\KategoriAnggaranResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKategoriAnggaran extends EditRecord
{
    protected static string $resource = KategoriAnggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
