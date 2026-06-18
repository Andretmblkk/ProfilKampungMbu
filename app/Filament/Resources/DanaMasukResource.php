<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DanaMasukResource\Pages;
use App\Models\DanaMasuk;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DanaMasukResource extends Resource
{
    protected static ?string $model = DanaMasuk::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Keuangan Kampung';
    protected static ?string $navigationLabel = 'Dana Masuk';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Dana Masuk')->columns(2)->schema([
                Forms\Components\TextInput::make('kode_transaksi')->required()->maxLength(80),
                Forms\Components\TextInput::make('sumber_dana')->required()->maxLength(160),
                Forms\Components\Select::make('kategori_anggaran_id')->relationship('kategoriAnggaran', 'nama')->searchable()->preload(),
                Forms\Components\TextInput::make('nominal')->numeric()->prefix('Rp')->required(),
                Forms\Components\DatePicker::make('tanggal')->required(),
                Forms\Components\Select::make('status')->options(['menunggu' => 'Menunggu', 'terverifikasi' => 'Terverifikasi', 'ditolak' => 'Ditolak'])->default('menunggu'),
                Forms\Components\FileUpload::make('bukti_path')->label('Bukti Transfer')->directory('bukti/dana-masuk')->acceptedFileTypes(['application/pdf', 'image/*']),
                Forms\Components\Textarea::make('keterangan')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_transaksi')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('sumber_dana')->searchable(),
                Tables\Columns\TextColumn::make('nominal')->money('IDR')->sortable(),
                Tables\Columns\TextColumn::make('tanggal')->date('d M Y')->sortable(),
                Tables\Columns\BadgeColumn::make('status')->colors(['warning' => 'menunggu', 'success' => 'terverifikasi', 'danger' => 'ditolak']),
            ])
            ->filters([Tables\Filters\SelectFilter::make('status')->options(['menunggu' => 'Menunggu', 'terverifikasi' => 'Terverifikasi', 'ditolak' => 'Ditolak'])])
            ->actions([Tables\Actions\ViewAction::make(), Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListDanaMasuks::route('/'), 'create' => Pages\CreateDanaMasuk::route('/create'), 'edit' => Pages\EditDanaMasuk::route('/{record}/edit')];
    }
}
