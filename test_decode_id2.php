<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$r = DB::table('kelurahans')->where('id', 2)->first();
$raw = $r->geometri;

echo "Original:\n";
echo "  Type: " . gettype($raw) . "\n";
echo "  Length: " . strlen($raw) . "\n";
echo "  First 100 chars:\n";
var_dump(substr($raw, 0, 100));

echo "\n\nAfter trim+stripslashes:\n";
$cleaned = trim($raw, '"');
$cleaned = stripslashes($cleaned);
echo "  Length: " . strlen($cleaned) . "\n";
echo "  First 100 chars:\n";
var_dump(substr($cleaned, 0, 100));

echo "\n\nTrying json_decode on original:\n";
$g1 = @json_decode($raw, false);
echo "  Error: " . json_last_error() . "\n";
echo "  Result: " . ($g1 ? 'OK' : 'NULL') . "\n";

echo "\n\nTrying json_decode after cleaning:\n";
$g2 = @json_decode($cleaned, false);
echo "  Error: " . json_last_error() . "\n";
echo "  Result: " . ($g2 ? 'OK' : 'NULL') . "\n";

if ($g2) {
    echo "  Type: " . $g2->type . "\n";
}
