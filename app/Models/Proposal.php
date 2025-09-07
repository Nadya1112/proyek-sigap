<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proposal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'kompleks_id',
        'nama_pengaju',
        'kontak_pengaju',
        'dokumen_proposal',
        'catatan',
        'status',
    ];

    /**
     * Mendefinisikan relasi "belongsTo" ke model User.
     * Setiap proposal dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mendefinisikan relasi "belongsTo" ke model Komplek.
     * Setiap proposal merujuk ke satu komplek.
     */
    public function kompleks()
    {
        return $this->belongsTo(Kompleks::class, 'kompleks_id');
    }
}