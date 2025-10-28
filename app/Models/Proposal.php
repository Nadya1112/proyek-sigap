<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo

class Proposal extends Model
{
    use HasFactory;

    /**
     * Definisikan konstanta untuk setiap status proposal baru.
     */
    public const STATUS_DIAJUKAN = 'Diajukan';
    public const STATUS_DIVERIFIKASI_JF = 'Diverifikasi JF';
    public const STATUS_DISETUJUI_KABID = 'Disetujui Kabid';
    public const STATUS_DISETUJUI_KADIS = 'Disetujui Kadis'; // Status final "Disetujui"
    public const STATUS_DITOLAK = 'Ditolak';
    
    protected $guarded = [];

    /**
     * Relasi ke User.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke Komplek (DIAKTIFKAN).
     */
    public function komplek(): BelongsTo // Pastikan nama fungsi 'komplek' (sesuai panggilan Filament)
    {
        // 'kompleks_id' adalah nama foreign key di tabel 'proposals'
        return $this->belongsTo(Komplek::class, 'kompleks_id');
    }
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}