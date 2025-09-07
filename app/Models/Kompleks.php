<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Komplek extends Model {
    use HasFactory;
    protected $table = 'kompleks';
    protected $guarded = [];
    public function kelurahan() {
        return $this->belongsTo(Kelurahan::class);
    }
}