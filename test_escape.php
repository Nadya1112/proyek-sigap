<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$r = DB::table('kelurahans')->where('id', 2)->first();
$raw = $r->geometri;

// Check if it has escaped quotes inside
echo "Has backslash+quote (\\\"): " . (strpos($raw, '\\"') !== false ? 'YES' : 'NO') . "\n";
echo "First 10 chars hex: ";
for ($i=0;$i<10;$i++) {
    printf("%02X ", ord($raw[$i]));
}
echo "\n\n";

// Try to unescape all quotes
$unescaped = str_replace('\\"', '"', $raw);
echo "After str_replace backslash+quote:\n";
echo "First 100 chars:\n";
echo substr($unescaped, 0, 100) . "\n\n";

$g = @json_decode($unescaped, false);
echo "json_decode result: " . (json_last_error() === JSON_ERROR_NONE ? 'OK' : 'FAIL (error ' . json_last_error() . ')') . "\n";
