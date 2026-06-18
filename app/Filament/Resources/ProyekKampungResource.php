<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProyekKampungResource\Pages;
use App\Models\ProyekKampung;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProyekKampungResource extends Resource
{
    protected static ?string $model = ProyekKampung::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationLabel = 'Proyek Kampung';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Profil Proyek')->columns(2)->schema([
                Forms\Components\TextInput::make('nama')->required()->maxLength(180),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('lokasi')->required(),
                Forms\Components\Select::make('kategori_anggaran_id')->relationship('kategoriAnggaran', 'nama')->searchable()->preload(),
                Forms\Components\TextInput::make('anggaran')->numeric()->prefix('Rp')->required(),
                Forms\Components\TextInput::make('realisasi')->numeric()->prefix('Rp')->default(0),
                Forms\Components\TextInput::make('progress')->numeric()->minValue(0)->maxValue(100)->suffix('%')->default(0),
                Forms\Components\Select::make('status')->options(['direncanakan' => 'Direncanakan', 'berjalan' => 'Berjalan', 'selesai' => 'Selesai'])->default('direncanakan'),
                Forms\Components\DatePicker::make('tanggal_mulai'),
                Forms\Components\DatePicker::make('tanggal_selesai'),
                Forms\Components\FileUpload::make('foto_path')->image()->directory('proyek/foto'),
                Forms\Components\FileUpload::make('dokumen_path')->directory('proyek/dokumen')->acceptedFileTypes(['application/pdf']),
                Forms\Components\Textarea::make('deskripsi')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('foto_path')->square(),
            Tables\Columns\TextColumn::make('nama')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('anggaran')->money('IDR')->sortable(),
            Tables\Columns\TextColumn::make('progress')->suffix('%')->sortable(),
            Tables\Columns\BadgeColumn::make('status')->colors(['gray' => 'direncanakan', 'warning' => 'berjalan', 'success' => 'selesai']),
        ])->actions([Tables\Actions\ViewAction::make(), Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListProyekKampungs::route('/'), 'create' => Pages\CreateProyekKampung::route('/create'), 'edit' => Pages\EditProyekKampung::route('/{record}/edit')];
    }
}
