<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Proposal extends Model
{
    use HasFactory, SoftDeletes;

    // Konstanta status proposal baru
    public const STATUS_DIAJUKAN = 'Diajukan';
    public const STATUS_DITERIMA = 'Diterima';
    public const STATUS_DIVERIFIKASI_JF = 'Diverifikasi JF';
    public const STATUS_DISETUJUI_KABID = 'Disetujui Kabid';
    public const STATUS_DISETUJUI_KADIS = 'Disetujui Kadis';
    public const STATUS_DITOLAK = 'Ditolak';
    
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function komplek(): BelongsTo
    {
        // 'kompleks_id' adalah foreign key di tabel 'proposals'
        return $this->belongsTo(Komplek::class, 'kompleks_id');
    }
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}