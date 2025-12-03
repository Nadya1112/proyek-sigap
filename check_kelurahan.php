<?php
// File untuk mengecek status data kelurahan

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "======================================\n";
echo "CEK STATUS DATA KELURAHAN\n";
echo "======================================\n\n";

// Total kelurahan
$totalKelurahan = DB::table('kelurahans')->count();
echo "1. Total Kelurahan: " . $totalKelurahan . "\n\n";

// Kelurahan dengan geometri
$kelurahhanWithGeom = DB::table('kelurahans')
    ->whereNotNull('geometri')
    ->where('geometri', '!=', '')
    ->count();
echo "2. Kelurahan dengan Geometri: " . $kelurahhanWithGeom . "\n\n";

// Detail semua kelurahan
echo "3. DETAIL SEMUA KELURAHAN:\n";
echo str_pad("ID", 5) . str_pad("NAMA KELURAHAN", 30) . str_pad("KECAMATAN", 25) . str_pad("STATUS GEOMETRI", 20) . "\n";
echo str_repeat("-", 80) . "\n";

$kelurahan = DB::table('kelurahans')
    ->leftJoin('kecamatans', 'kelurahans.kecamatan_id', '=', 'kecamatans.id')
    ->select(
        'kelurahans.id',
        'kelurahans.nama_kelurahan',
        'kecamatans.nama_kecamatan',
        DB::raw('IF(kelurahans.geometri IS NULL OR kelurahans.geometri = "", "❌ KOSONG", "✓ ADA") as status')
    )
    ->orderBy('kelurahans.nama_kelurahan')
    ->get();

foreach ($kelurahan as $row) {
    echo str_pad($row->id, 5) 
        . str_pad(substr($row->nama_kelurahan, 0, 28), 30) 
        . str_pad(substr($row->nama_kecamatan ?? '-', 0, 23), 25) 
        . str_pad($row->status, 20) . "\n";
}

echo "\n======================================\n";
echo "KESIMPULAN:\n";
echo "Total Kelurahan: " . $totalKelurahan . "\n";
echo "Dengan Geometri: " . $kelurahhanWithGeom . "\n";
echo "Tanpa Geometri: " . ($totalKelurahan - $kelurahhanWithGeom) . "\n";
echo "======================================\n";
?>
