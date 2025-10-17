<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// 1. Import class-class yang dibutuhkan untuk GIS
use MatanYadaev\EloquentSpatial\Objects\MultiPolygon;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;

// DIUBAH: Nama class disesuaikan menjadi 'Komplek' (tanpa 's')
// agar konsisten dengan pemanggilan di Controller Anda.
class Komplek extends Model
{
    // 2. Gunakan trait HasSpatial di sini
    use HasFactory, HasSpatial;

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
        'kelurahan_id',
        'alamat',
        'foto_komplek',
        'jumlah_sertifikat',
        'status_aset',
        'area', // 3. 'area' tetap ada di fillable
    ];

    /**
     * Casting tipe data, terutama untuk kolom spasial.
     * @var array
     */
    protected $casts = [
        // 4. Casting kolom 'area' menjadi objek MultiPolygon
        'area' => MultiPolygon::class,
    ];

    /**
     * Mendefinisikan relasi bahwa satu Kompleks dimiliki oleh satu Kelurahan.
     */
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }
}