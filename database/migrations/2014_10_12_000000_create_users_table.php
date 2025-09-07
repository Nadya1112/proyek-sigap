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

class UserResource extends Resource {
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    public static function form(Form $form): Form {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required(),
            Forms\Components\TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('kontak'),
            Forms\Components\TextInput::make('password')->password()->required()->dehydrateStateUsing(fn ($state) => Hash::make($state))->dehydrated(fn ($state) => filled($state))->required(fn (string $context): bool => $context === 'create'),
            Forms\Components\Select::make('role')->options(['admin' => 'Admin', 'pengguna' => 'Pengguna',])->required(),
        ]);
    }
    public static function table(Table $table): Table {
        return $table->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('role')->badge()->color(fn (string $state): string => match ($state) {
                    'admin' => 'danger', 'pengguna' => 'success',
                }),
            ])
            ->filters([Tables\Filters\SelectFilter::make('role')->options(['admin' => 'Admin', 'pengguna' => 'Pengguna',])])
            ->actions([Tables\Actions\EditAction::class,])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::class,]),]);
    }
    public static function getPages(): array {
        return [
            'index' => Pages\ListUsers::class,
            'create' => Pages\CreateUser::class,
            'edit' => Pages\EditUser::class,
        ];
    }
}