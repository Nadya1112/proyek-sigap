<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 1. Import class-class yang dibutuhkan untuk GIS (dari paket GRIMZY)
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait; // <-- Ganti dengan ini
use Grimzy\LaravelMysqlSpatial\Types\MultiPolygon; // <-- Ganti dengan ini

class Komplek extends Model
{
    // 2. Gunakan trait SpatialTrait di sini
    use HasFactory, SpatialTrait; // <-- Ganti dengan ini

    /**
     * Mendefinisikan nama tabel secara eksplisit (praktik yang baik).
     * @var string
     */
    protected $table = 'kompleks';

    /**
     * Kolom yang boleh diisi secara massal.
     * @var array
     */
    protected $fillable = [
        'nomor',
        'nama_komplek',
        // ... (pastikan semua kolom Anda ada di sini, termasuk 'area') ...
        'area', 
    ];

    /**
     * Tentukan kolom spasial untuk paket grimzy
     * @var array
     */
    protected $spatialFields = [
        'area' // <-- Tambahkan ini
    ];

    /**
     * Casting tipe data (jika diperlukan)
     * @var array
     */
    protected $casts = [
        'area' => MultiPolygon::class, // <-- Tambahkan ini jika perlu
    ];
}