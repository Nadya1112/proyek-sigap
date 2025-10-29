<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    // Definisikan semua konstanta role
    public const ROLE_ADMIN = 'admin'; // Role Super Admin
    public const ROLE_STAFF = 'Staff';
    public const ROLE_JF_PSU = 'JF PSU';
    public const ROLE_KABID = 'Kabid';
    public const ROLE_KADIS = 'Kadis';
    public const ROLE_PENGGUNA = 'pengguna';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'kontak',
        'verification_code',
        'verification_expires_at',
        'email_verified_at',
        'google_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
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

    /**
     * Tentukan siapa yang bisa mengakses Panel Admin Filament.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Izinkan semua role admin
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_STAFF,
            self::ROLE_JF_PSU,
            self::ROLE_KABID,
            self::ROLE_KADIS
        ]);
    }

    /**
     * Fungsi Helper untuk cek role
     */
    public function isSuperAdmin(): bool { return $this->role === self::ROLE_ADMIN; }
    public function isStaff(): bool { return $this->role === self::ROLE_STAFF; }
    public function isJfPsu(): bool { return $this->role === self::ROLE_JF_PSU; }
    public function isKabid(): bool { return $this->role === self::ROLE_KABID; }
    public function isKadis(): bool { return $this->role === self::ROLE_KADIS; }
    
    public function isAdmin(): bool 
    {
        return $this->canAccessPanel(new Panel());
    }

    // Relasi
    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'user_id');
    }

    public function pengaduans(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'user_id');
    }
}