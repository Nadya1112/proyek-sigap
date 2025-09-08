<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Komplek extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomor',
        'nama_komplek',
        'kelurahan_id',
        'alamat',
        'foto_komplek',
        'jumlah_sertifikat',
        'status_aset',
    ];

    public function kelurahan() { return $this->belongsTo(Kelurahan::class); }
}
