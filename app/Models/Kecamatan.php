<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    /**
     * Tentukan kolom mana yang boleh diisi.
     */
    protected $fillable = [
        'nama_kecamatan',
        'warna',
        'geometri',
    ];

    /**
     * Beri tahu Laravel bahwa 'geometri' adalah array/json.
     * Ini SANGAT PENTING agar API berfungsi.
     */
    protected $casts = [
        'geometri' => 'array', 
    ];

    /**
     * (Relasi ini dari file Anda sebelumnya, biarkan saja)
     */
    public function kelurahans() {
        return $this->hasMany(Kelurahan::class);
    }
}