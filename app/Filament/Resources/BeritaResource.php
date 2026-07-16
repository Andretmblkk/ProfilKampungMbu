<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BeritaResource\Pages;
use App\Models\Berita;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class BeritaResource extends Resource
{
    protected static ?string $model = Berita::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $navigationGroup = 'Konten Publik';

    protected static ?string $navigationLabel = 'Berita Kampung';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Publikasi Berita')->columns(2)->schema([
                Forms\Components\TextInput::make('judul')
                    ->required()
                    ->maxLength(200)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (?string $state, Forms\Set $set) => $set('slug', Str::slug($state ?? ''))),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('ringkasan')->required()->maxLength(500)->columnSpanFull(),
                Forms\Components\RichEditor::make('isi')->required()->columnSpanFull(),
                Forms\Components\FileUpload::make('gambar_path')->image()->directory('berita')->disk('public'),
                Forms\Components\Select::make('status')
                    ->options(['draft' => 'Draft', 'terbit' => 'Terbit'])
                    ->default('draft')
                    ->required(),
                Forms\Components\DateTimePicker::make('published_at')
                    ->label('Waktu Terbit')
                    ->seconds(false),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('gambar_path')->disk('public')->square(),
                Tables\Columns\TextColumn::make('judul')->searchable()->sortable(),
                Tables\Columns\BadgeColumn::make('status')->colors(['gray' => 'draft', 'success' => 'terbit']),
                Tables\Columns\TextColumn::make('published_at')->dateTime('d M Y H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options(['draft' => 'Draft', 'terbit' => 'Terbit']),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBeritas::route('/'),
            'create' => Pages\CreateBerita::route('/create'),
            'edit' => Pages\EditBerita::route('/{record}/edit'),
        ];
    }
}
