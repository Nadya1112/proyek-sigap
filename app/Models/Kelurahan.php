<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Kelurahan extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function kecamatan() {
        return $this->belongsTo(Kecamatan::class);
    }
    /**
     * Mendefinisikan relasi bahwa satu Kelurahan memiliki banyak Komplek.
     */
    public function kompleks() // Gunakan nama jamak
    {
        // 'kelurahan_id' adalah foreign key di tabel 'kompleks'
        return $this->hasMany(Komplek::class, 'kelurahan_id');
    }
}