<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class);
    }
}
