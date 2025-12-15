<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

// Hapus import untuk MatanYadaev

class Komplek extends Model
{
    use HasFactory, SoftDeletes; // Hapus HasSpatial untuk sementara

    protected $table = 'kompleks';

    protected $fillable = [
        'kelurahan_id',
        'nomor',
        'nama_komplek',
        'nama_pengembang',
        'alamat_komplek',
        'foto_komplek',
        'jumlah_sertifikat',
        'jumlah_unit',
        'status_aset',
        'fasilitas_ibadah',
        'fasilitas_umum',
        'fasilitas_pendidikan',
        'fasilitas_kesehatan',
        'latitude',
        'longitude',
        // 'area', // Dinonaktifkan sementara
    ];

    // Casts untuk 'area' dinonaktifkan sementara
    // protected $casts = [
    //     'area' => MultiPolygon::class,
    // ];

    public function kelurahan(): BelongsTo
    {
         return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }
}