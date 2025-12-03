<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$r1 = DB::table('kelurahans')->where('id', 1)->first();
$r3 = DB::table('kelurahans')->where('id', 3)->first();

echo "=== ID 1 (FAIL) ===\n";
echo "Raw length: " . strlen($r1->geometri) . "\n";
echo "First 50 chars: " . substr($r1->geometri, 0, 50) . "\n";
echo "First 50 hex: ";
for ($i=0; $i<50; $i++) printf("%02X ", ord($r1->geometri[$i]));
echo "\n\n";

// After cleaning
$c1 = preg_replace('/[\r\n\t ]+/', '', $r1->geometri);
echo "After cleaning first 100: " . substr($c1, 0, 100) . "\n";
echo "After cleaning first 100 hex: ";
for ($i=0; $i<min(100, strlen($c1)); $i++) printf("%02X ", ord($c1[$i]));
echo "\n\n";

echo "=== ID 3 (OK) ===\n";
echo "Raw length: " . strlen($r3->geometri) . "\n";
echo "First 50 chars: " . substr($r3->geometri, 0, 50) . "\n";
echo "First 50 hex: ";
for ($i=0; $i<50; $i++) printf("%02X ", ord($r3->geometri[$i]));
echo "\n\n";

// After cleaning
$c3 = preg_replace('/[\r\n\t ]+/', '', $r3->geometri);
echo "After cleaning first 100: " . substr($c3, 0, 100) . "\n";
echo "After cleaning first 100 hex: ";
for ($i=0; $i<min(100, strlen($c3)); $i++) printf("%02X ", ord($c3[$i]));
echo "\n";
