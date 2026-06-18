<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengeluaranResource\Pages;
use App\Models\Pengeluaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PengeluaranResource extends Resource
{
    protected static ?string $model = Pengeluaran::class;
    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'Keuangan Kampung';
    protected static ?string $navigationLabel = 'Pengeluaran';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detail Pengeluaran')->columns(2)->schema([
                Forms\Components\TextInput::make('kode_transaksi')->required(),
                Forms\Components\TextInput::make('uraian')->required(),
                Forms\Components\Select::make('kategori_anggaran_id')->relationship('kategoriAnggaran', 'nama')->searchable()->preload(),
                Forms\Components\Select::make('proyek_kampung_id')->relationship('proyekKampung', 'nama')->searchable()->preload(),
                Forms\Components\TextInput::make('nominal')->numeric()->prefix('Rp')->required(),
                Forms\Components\DatePicker::make('tanggal')->required(),
                Forms\Components\TextInput::make('penerima'),
                Forms\Components\Select::make('status')->options(['menunggu' => 'Menunggu', 'terverifikasi' => 'Terverifikasi', 'ditolak' => 'Ditolak'])->default('menunggu'),
                Forms\Components\FileUpload::make('bukti_path')->directory('bukti/pengeluaran')->acceptedFileTypes(['application/pdf', 'image/*'])->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('uraian')->searchable()->description(fn (Pengeluaran $record): string => $record->kode_transaksi),
            Tables\Columns\TextColumn::make('nominal')->money('IDR')->sortable(),
            Tables\Columns\TextColumn::make('tanggal')->date('d M Y')->sortable(),
            Tables\Columns\BadgeColumn::make('status')->colors(['warning' => 'menunggu', 'success' => 'terverifikasi', 'danger' => 'ditolak']),
        ])->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListPengeluarans::route('/'), 'create' => Pages\CreatePengeluaran::route('/create'), 'edit' => Pages\EditPengeluaran::route('/{record}/edit')];
    }
}
