<?php

namespace App\Filament\Resources\ProyekKampungResource\Pages;

use App\Filament\Resources\ProyekKampungResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProyekKampungs extends ListRecords
{
    protected static string $resource = ProyekKampungResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
