<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Administrasi';
    protected static ?string $navigationLabel = 'Manajemen Pengguna';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Akun Pengguna')->columns(2)->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('role')->options(['administrator' => 'Administrator', 'operator' => 'Operator'])->default('operator')->required(),
                Forms\Components\Select::make('status')->options(['aktif' => 'Aktif', 'nonaktif' => 'Non-Aktif'])->default('aktif')->required(),
                Forms\Components\FileUpload::make('avatar')->image()->directory('avatars'),
                Forms\Components\TextInput::make('password')->password()->dehydrateStateUsing(fn (?string $state) => filled($state) ? Hash::make($state) : null)->dehydrated(fn (?string $state) => filled($state))->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('avatar')->circular(),
            Tables\Columns\TextColumn::make('name')->searchable()->description(fn (User $record): string => $record->email),
            Tables\Columns\BadgeColumn::make('role')->colors(['primary' => 'administrator', 'gray' => 'operator']),
            Tables\Columns\BadgeColumn::make('status')->colors(['success' => 'aktif', 'gray' => 'nonaktif']),
            Tables\Columns\TextColumn::make('last_login_at')->dateTime('d M Y H:i'),
        ])->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => Pages\ListUsers::route('/'), 'create' => Pages\CreateUser::route('/create'), 'edit' => Pages\EditUser::route('/{record}/edit')];
    }
}
