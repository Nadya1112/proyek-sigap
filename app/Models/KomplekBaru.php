<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KomplekBaru extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kecamatan_id',
        'kelurahan_id',
        'nama_komplek',
        'alamat',
        'nomor_hp',
        'status',
        'catatan_admin',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function kecamatan(): BelongsTo { return $this->belongsTo(Kecamatan::class); }
    public function kelurahan(): BelongsTo { return $this->belongsTo(Kelurahan::class); }
}