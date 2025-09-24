<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kontak',

        // kolom verifikasi
        'verification_code',
        'verification_expires_at',

        'email_verified_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',

        // jangan tampilkan di array/json
        'verification_code',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'        => 'datetime',
            'verification_expires_at'  => 'datetime',
            'password'                 => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === 'admin';
    }
}
