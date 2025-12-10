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
use App\Filament\Resources\UserResource\RelationManagers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Actions\ForceDeleteAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\DeleteBulkAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    
    // PERMINTAAN 1: Mengganti judul
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
                ->dehydrateStateUsing(fn ($state) => Hash::make($state)) // Hash password saat disimpan
                ->dehydrated(fn ($state) => filled($state)) // Hanya update jika diisi
                ->required(fn (string $context): bool => $context === 'create'), // Wajib saat buat baru
            
            // PERBAIKAN: Tambahkan semua role baru ke options
            Forms\Components\Select::make('role')
                ->label('Role')
                ->options([
                    User::ROLE_ADMIN => 'Admin (Super Admin)', // Gunakan konstanta dari Model User
                    User::ROLE_STAFF => 'Staff',
                    User::ROLE_JF_PSU => 'JF PSU',
                    User::ROLE_KABID => 'Kabid',
                    User::ROLE_KADIS => 'Kadis',
                    User::ROLE_PENGGUNA => 'Pengguna',
                ])
                ->required(),
        ]);
    }

    public static function table(Table $table): Table
    {
        /** @var User $user */
        $user = Auth::user(); // Ambil user yang sedang login

        return $table->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable(),
                
                // PERBAIKAN ERROR: Tangani semua kasus role baru
                Tables\Columns\TextColumn::make('role')
                    ->label('Role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        User::ROLE_ADMIN => 'danger',
                        User::ROLE_STAFF => 'blue',
                        User::ROLE_JF_PSU => 'info',
                        User::ROLE_KABID => 'warning',
                        User::ROLE_KADIS => 'primary',
                        User::ROLE_PENGGUNA => 'success',
                        default => 'gray', // Fallback untuk status lain
                    }),
            ])
            ->filters([
                TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('role')
                    ->label('Filter Berdasarkan Role')
                    ->options([
                        User::ROLE_ADMIN => 'Admin (Super Admin)',
                        User::ROLE_STAFF => 'Staff',
                        User::ROLE_JF_PSU => 'JF PSU',
                        User::ROLE_KABID => 'Kabid',
                        User::ROLE_KADIS => 'Kadis',
                        User::ROLE_PENGGUNA => 'Pengguna',
                    ])
                    ->multiple(), // Izinkan filter beberapa role
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn () => !$user->isSuperAdmin()),
                
                Tables\Actions\EditAction::make()
                    ->label('Ubah')
                    ->visible(fn () => $user->isSuperAdmin()),
                
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => $user->isSuperAdmin()),
                ForceDeleteAction::make()
                    ->visible(fn () => $user->isSuperAdmin()),
                RestoreAction::make()
                    ->visible(fn () => $user->isSuperAdmin()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(fn () => $user->isSuperAdmin()),
                    ForceDeleteBulkAction::make()
                        ->visible(fn () => $user->isSuperAdmin()),
                    RestoreBulkAction::make()
                        ->visible(fn () => $user->isSuperAdmin()),
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
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->isSuperAdmin();
    }

    public static function canCreate(): bool
    { 
        return Auth::user()->isSuperAdmin(); 
    }

    public static function canEdit(Model $record): bool
    { 
        return Auth::user()->isSuperAdmin(); 
    }

    public static function canDelete(Model $record): bool
    { 
        return Auth::user()->isSuperAdmin(); 
    }

    public static function canDeleteAny(): bool
    { 
        return Auth::user()->isSuperAdmin(); 
    }
}