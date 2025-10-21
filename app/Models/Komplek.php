<?php

namespace App\Models; // <-- Perbaikan di sini

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// Import dari paket MATAN YADAEV
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

class Komplek extends Model
{
    // Gunakan trait HasSpatial
    use HasFactory, HasSpatial;

    protected $table = 'kompleks';

    protected $fillable = [
        'kelurahan_id', // Pastikan semua kolom yang relevan ada di sini
        'nomor',
        'nama_komplek',
        'foto_komplek',
        'jumlah_sertifikat',
        'status_aset',
        'area',
    ];

    // Properti $spatialFields tidak diperlukan oleh Matan Yadaev

    protected $casts = [
        'area' => MultiPolygon::class, // Ini sudah benar
        // 'created_at' => 'datetime', // Opsional
        // 'updated_at' => 'datetime', // Opsional
    ];

    /**
      * Relasi ke Kelurahan.
      */
     public function kelurahan()
     {
         return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
     }
}