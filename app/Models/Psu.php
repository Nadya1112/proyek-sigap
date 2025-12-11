<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Psu extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'psu';
    protected $guarded = [];
    public function komplek() {
        return $this->belongsTo(Komplek::class, 'kompleks_id');
    }
}