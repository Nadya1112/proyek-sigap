<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Psu extends Model {
    use HasFactory;
    protected $table = 'psu';
    protected $guarded = [];
    public function komplek() {
        return $this->belongsTo(Komplek::class, 'kompleks_id');
    }
}