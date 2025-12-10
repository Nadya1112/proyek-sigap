<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Regulasi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'judul',
        'tahun',
        'path',
        'nama_file_asli',
        'tipe_file',
        'ukuran_file',
    ];
}