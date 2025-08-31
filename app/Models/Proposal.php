<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Proposal extends Model
{
    protected $fillable = ['nama_pengaju','kontak','nama_perumahan','alamat','file_path','status','catatan'];
}
