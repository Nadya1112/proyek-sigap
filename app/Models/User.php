<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // Pastikan ini di-import

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    /**
     * Definisikan konstanta untuk setiap role agar kode lebih mudah dibaca.
     */
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
        'google_id', // Pastikan google_id ditambahkan jika Anda menggunakannya
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_code', // jangan tampilkan di array/json
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
     * Izinkan semua role admin baru untuk mengakses panel Filament.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Periksa apakah role pengguna termasuk dalam daftar role admin yang diizinkan
        return in_array($this->role, [
            self::ROLE_STAFF,
            self::ROLE_JF_PSU,
            self::ROLE_KABID,
            self::ROLE_KADIS
        ]);
    }

    /**
     * Fungsi Helper (Sangat Bermanfaat untuk Filament)
     */
    public function isStaff(): bool { return $this->role === self::ROLE_STAFF; }
    public function isJfPsu(): bool { return $this->role === self::ROLE_JF_PSU; }
    public function isKabid(): bool { return $this->role === self::ROLE_KABID; }
    public function isKadis(): bool { return $this->role === self::ROLE_KADIS; }
    public function isAdmin(): bool { // Fungsi umum untuk semua admin
        return in_array($this->role, [self::ROLE_STAFF, self::ROLE_JF_PSU, self::ROLE_KABID, self::ROLE_KADIS]);
    }

    /**
     * Relasi ke Proposal (Satu User bisa memiliki banyak Proposal).
     */
    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class, 'user_id');
    }

    /**
     * Relasi ke Pengaduan (Satu User bisa memiliki banyak Pengaduan).
     */
    public function pengaduans(): HasMany
    {
        return $this->hasMany(Pengaduan::class, 'user_id');
    }
}