<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanWargaResource\Pages;
use App\Models\LaporanWarga;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LaporanWargaResource extends Resource
{
    protected static ?string $model = LaporanWarga::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Layanan Publik';
    protected static ?string $navigationLabel = 'Laporan Warga';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Detail Laporan')->columns(2)->schema([
                Forms\Components\TextInput::make('nomor_tiket')->disabled()->dehydrated(false),
                Forms\Components\TextInput::make('nama_pelapor')->required(),
                Forms\Components\TextInput::make('kontak')->required(),
                Forms\Components\Select::make('kategori')->options([
                    'Dana Kampung' => 'Dana Kampung',
                    'Proyek Pembangunan' => 'Proyek Pembangunan',
                    'Bantuan Sosial' => 'Bantuan Sosial',
                    'Layanan Administrasi' => 'Layanan Administrasi',
                    'Lainnya' => 'Lainnya',
                ])->required(),
                Forms\Components\Select::make('status')->options([
                    'baru' => 'Baru',
                    'diproses' => 'Diproses',
                    'selesai' => 'Selesai',
                    'ditolak' => 'Ditolak',
                ])->required(),
                Forms\Components\FileUpload::make('lampiran_path')->directory('laporan-warga')->acceptedFileTypes(['application/pdf', 'image/*']),
                Forms\Components\Textarea::make('isi_laporan')->required()->columnSpanFull(),
                Forms\Components\Textarea::make('tanggapan_admin')->label('Tanggapan Admin')->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('nomor_tiket')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('nama_pelapor')->searchable(),
            Tables\Columns\TextColumn::make('kategori')->badge(),
            Tables\Columns\BadgeColumn::make('status')->colors([
                'info' => 'baru',
                'warning' => 'diproses',
                'success' => 'selesai',
                'danger' => 'ditolak',
            ]),
            Tables\Columns\TextColumn::make('created_at')->dateTime('d M Y H:i')->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('status')->options([
                'baru' => 'Baru',
                'diproses' => 'Diproses',
                'selesai' => 'Selesai',
                'ditolak' => 'Ditolak',
            ]),
        ])->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporanWargas::route('/'),
            'create' => Pages\CreateLaporanWarga::route('/create'),
            'edit' => Pages\EditLaporanWarga::route('/{record}/edit'),
        ];
    }
}
