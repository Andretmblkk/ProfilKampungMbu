<?php

namespace App\Filament\Resources\KategoriAnggaranResource\Pages;

use App\Filament\Resources\KategoriAnggaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKategoriAnggarans extends ListRecords
{
    protected static string $resource = KategoriAnggaranResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
