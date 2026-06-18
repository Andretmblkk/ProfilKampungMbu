<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KategoriAnggaranResource\Pages;
use App\Models\KategoriAnggaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KategoriAnggaranResource extends Resource
{
    protected static ?string $model = KategoriAnggaran::class;
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Keuangan Kampung';
    protected static ?string $navigationLabel = 'Kategori Anggaran';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('nama')->required(),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\ColorPicker::make('warna')->required(),
            Forms\Components\TextInput::make('ikon')->required(),
            Forms\Components\TextInput::make('pagu_anggaran')->numeric()->prefix('Rp'),
            Forms\Components\Textarea::make('deskripsi')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ColorColumn::make('warna'),
            Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('pagu_anggaran')->money('IDR')->sortable(),
            Tables\Columns\TextColumn::make('updated_at')->dateTime('d M Y H:i')->sortable(),
        ])->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListKategoriAnggarans::route('/'), 'create' => Pages\CreateKategoriAnggaran::route('/create'), 'edit' => Pages\EditKategoriAnggaran::route('/{record}/edit')];
    }
}
