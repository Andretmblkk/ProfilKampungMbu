<?php

namespace App\Filament\Resources\DanaMasukResource\Pages;

use App\Filament\Resources\DanaMasukResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDanaMasuk extends EditRecord
{
    protected static string $resource = DanaMasukResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
