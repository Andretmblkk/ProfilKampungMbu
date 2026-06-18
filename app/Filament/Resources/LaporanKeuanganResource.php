<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanKeuanganResource\Pages;
use App\Models\LaporanKeuangan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LaporanKeuanganResource extends Resource
{
    protected static ?string $model = LaporanKeuangan::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Keuangan Kampung';
    protected static ?string $navigationLabel = 'Laporan Keuangan';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Arsip Laporan')->columns(2)->schema([
                Forms\Components\TextInput::make('judul')->required(),
                Forms\Components\Select::make('kategori')->options(['Administrasi' => 'Administrasi', 'Keuangan' => 'Keuangan', 'Pembangunan' => 'Pembangunan'])->required(),
                Forms\Components\TextInput::make('periode')->required(),
                Forms\Components\DatePicker::make('tanggal_laporan')->required(),
                Forms\Components\FileUpload::make('file_path')->directory('laporan-keuangan')->acceptedFileTypes(['application/pdf', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/csv'])->required()->columnSpanFull(),
                Forms\Components\Select::make('status')->options(['menunggu' => 'Menunggu', 'terverifikasi' => 'Terverifikasi', 'ditolak' => 'Ditolak'])->default('menunggu'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('judul')->searchable()->description(fn (LaporanKeuangan $record): string => strtoupper($record->file_type).' Document'),
            Tables\Columns\TextColumn::make('kategori')->sortable(),
            Tables\Columns\TextColumn::make('tanggal_laporan')->date('d M Y')->sortable(),
            Tables\Columns\BadgeColumn::make('status')->colors(['warning' => 'menunggu', 'success' => 'terverifikasi', 'danger' => 'ditolak']),
        ])->actions([Tables\Actions\ViewAction::make(), Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListLaporanKeuangans::route('/'), 'create' => Pages\CreateLaporanKeuangan::route('/create'), 'edit' => Pages\EditLaporanKeuangan::route('/{record}/edit')];
    }
}
