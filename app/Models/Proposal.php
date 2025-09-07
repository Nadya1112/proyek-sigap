<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Proposal extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function user() {
        return $this->belongsTo(User::class);
    }
    public function komplek() {
        return $this->belongsTo(Komplek::class, 'kompleks_id');
    }
}