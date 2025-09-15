<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Document extends Model
{
    protected $fillable = ['title','slug','filename','mime','size_kb','year'];

    // Biar slug otomatis kalau kosong
    protected static function booted(): void
    {
        static::saving(function (self $doc) {
            if (blank($doc->slug) && filled($doc->title)) {
                $doc->slug = Str::slug($doc->title);
            }
        });
    }
}
