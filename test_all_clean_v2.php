<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

for ($id = 1; $id <= 5; $id++) {
    $r = DB::table('kelurahans')->where('id', $id)->first();
    if (!$r) continue;
    
    $raw = $r->geometri;
    
    // Clean
    $raw = trim($raw, '"');
    $raw = stripslashes($raw);
    $raw = preg_replace('/[\r\n\t ]+/', '', $raw);
    
    // Try decode
    $geo = @json_decode($raw, false);
    $err = json_last_error();
    $msg = json_last_error_msg();
    
    echo "$id | {$r->nama_kelurahan} | err=$err | $msg\n";
    if ($err !== JSON_ERROR_NONE) {
        // Show last 100 chars
        echo "  Last 100: " . substr($raw, -100) . "\n";
    }
}
