<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

for ($id = 1; $id <= 5; $id++) {
    $r = DB::table('kelurahans')->where('id', $id)->first();
    $raw = $r->geometri;
    
    // Aggressive cleaning dari controller
    $raw = trim($raw, '"');
    $raw = stripslashes($raw);
    $raw = preg_replace('/\s+/', '', $raw);
    
    $g = @json_decode($raw, false);
    $err = json_last_error();
    $msg = $err === JSON_ERROR_NONE ? 'OK' : 'ERR' . $err;
    
    // Jika error, cari karakter awal yang error
    $jsonErr = json_last_error_msg();
    echo "$id | " . $r->nama_kelurahan . " | $msg | " . $jsonErr . "\n";
    
    if ($err !== JSON_ERROR_NONE) {
        // Print first 50 chars after clean
        echo "    First 50: " . substr($raw, 0, 50) . "\n";
    }
}
