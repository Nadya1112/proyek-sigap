<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import

class Pengaduan extends Model
{
    use HasFactory;
    protected $guarded = [];

    // Konstanta status disamakan dengan Proposal
    public const STATUS_DIAJUKAN = 'Diajukan';
    public const STATUS_DITERIMA = 'Diterima';
    public const STATUS_DIVERIFIKASI_JF = 'Diverifikasi JF';
    public const STATUS_DISETUJUI_KABID = 'Disetujui Kabid';
    public const STATUS_DISETUJUI_KADIS = 'Disetujui Kadis';
    public const STATUS_DITOLAK = 'Ditolak';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}