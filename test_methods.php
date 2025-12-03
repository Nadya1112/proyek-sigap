<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$r = DB::table('kelurahans')->where('id', 1)->first();
$raw = $r->geometri;

echo "Original length: " . strlen($raw) . "\n";
echo "First 20 bytes (hex): ";
for ($i=0; $i<20; $i++) {
    printf("%02X ", ord($raw[$i]));
}
echo "\n\n";

// Method 1: Basic cleaning
$m1 = trim($raw, '"');
$m1 = stripslashes($m1);
$m1 = preg_replace('/[\r\n\t ]+/', '', $m1);
$g1 = @json_decode($m1, false);
echo "Method 1 (current): " . (json_last_error() === JSON_ERROR_NONE ? 'OK' : 'FAIL') . "\n";

// Method 2: Remove ALL ASCII control characters
$m2 = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $raw);
$m2 = trim($m2, '"');
$m2 = stripslashes($m2);
$m2 = preg_replace('/\s+/', '', $m2);
$g2 = @json_decode($m2, false);
echo "Method 2 (remove controls): " . (json_last_error() === JSON_ERROR_NONE ? 'OK' : 'FAIL') . "\n";

// Method 3: Use mbstring to strip non-printable
if (function_exists('mb_convert_encoding')) {
    $m3 = mb_convert_encoding($raw, 'UTF-8', 'UTF-8');
    $m3 = preg_replace('/[\x00-\x1F\x7F]/u', '', $m3);
    $m3 = trim($m3, '"');
    $m3 = stripslashes($m3);
    $m3 = preg_replace('/\s+/', '', $m3);
    $g3 = @json_decode($m3, false);
    echo "Method 3 (mbstring): " . (json_last_error() === JSON_ERROR_NONE ? 'OK' : 'FAIL') . "\n";
}
