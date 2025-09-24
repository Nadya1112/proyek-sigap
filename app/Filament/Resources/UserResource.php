<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $title = 'Pengguna';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form {
        return $form->schema([
            Forms\Components\TextInput::make('name')
            ->label('Nama')
            ->required(),
            Forms\Components\TextInput::make('email')
            ->label('Email')
            ->email()
            ->required()
            ->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('kontak')
            ->label('Kontak'),
            Forms\Components\TextInput::make('password')
            ->label('Password')
            ->password()
            ->required()
            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
            ->dehydrated(fn ($state) => filled($state))
            ->required(fn (string $context): bool => $context === 'create'),
            Forms\Components\Select::make('role')
            ->label('Role')
            ->options([
                'admin' => 'Admin', 
                'pengguna' => 'Pengguna',
                ])
            ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
                Tables\Columns\TextColumn::make('name')
                ->label('Nama')
                ->searchable(),
                Tables\Columns\TextColumn::make('email')
                ->label('Email')
                ->searchable(),
                Tables\Columns\TextColumn::make('role')
                ->label('Role')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'admin' => 'danger', 
                    'pengguna' => 'success',
                }),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
