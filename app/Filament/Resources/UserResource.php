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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    
    // PERMINTAAN 1: Mengganti judul
    protected static ?string $title = 'Pengguna';
    protected static ?string $navigationLabel = 'Pengguna';
    protected static ?string $navigationGroup = 'Manajemen Admin'; // Grup baru
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
                        User::ROLE_STAFF => 'gray',
                        User::ROLE_JF_PSU => 'info',
                        User::ROLE_KABID => 'warning',
                        User::ROLE_KADIS => 'primary',
                        User::ROLE_PENGGUNA => 'success',
                        default => 'gray', // Fallback untuk status lain
                    }),
            ])
            ->filters([
                // PERMINTAAN 3: Tambahkan filter berdasarkan role
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
                // Aksi 'View' untuk role Read-Only
                Tables\Actions\ViewAction::make()
                    ->visible(fn () => !$user->isSuperAdmin()), // Tampil jika BUKAN super admin
                
                // Aksi 'Edit' untuk Super Admin
                Tables\Actions\EditAction::make()
                    ->label('Ubah') // Ganti label 'Edit' menjadi 'Ubah'
                    ->visible(fn () => $user->isSuperAdmin()), // Tampil HANYA jika super admin
                
                // PERMINTAAN 2: Tambahkan tombol Delete
                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => $user->isSuperAdmin()), // Tampil HANYA jika super admin
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
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
            // Tambahkan halaman View
            'view' => Pages\ViewUser::route('/{record}'),
        ];
    }

    // --- IMPLEMENTASI HAK AKSES SESUAI PERMINTAAN ---

    // Hanya Super Admin (role 'admin') yang bisa membuat user baru.
    public static function canCreate(): bool
    { 
        return Auth::user()->isSuperAdmin(); 
    }

    // Hanya Super Admin (role 'admin') yang bisa mengedit user.
    public static function canEdit(Model $record): bool
    { 
        return Auth::user()->isSuperAdmin(); 
    }

    // Hanya Super Admin (role 'admin') yang bisa menghapus user.
    public static function canDelete(Model $record): bool
    { 
        return Auth::user()->isSuperAdmin(); 
    }

    // Hanya Super Admin (role 'admin') yang bisa bulk delete.
    public static function canDeleteAny(): bool
    { 
        return Auth::user()->isSuperAdmin(); 
    }

    // SEMUA role admin bisa melihat (Read) daftar user.
    // Ini sudah ditangani oleh fungsi canAccessPanel() di Model User.
}