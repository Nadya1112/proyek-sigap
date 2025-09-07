<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteStatistic extends Model {
    use HasFactory;
    protected $table = 'site_statistics';
    public $timestamps = false;
    protected $fillable = ['visitor_count'];
}