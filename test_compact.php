<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$r = DB::table('kelurahans')->where('id', 2)->first();
$raw = $r->geometri;

// Try compacting: remove all whitespace including \r\n
$compact = preg_replace('/\s+/', '', $raw);
echo "After whitespace removal, length: " . strlen($compact) . "\n";
echo "First 150: " . substr($compact, 0, 150) . "\n\n";

$g = @json_decode($compact, false);
$err = json_last_error();
echo "json_decode result: " . ($err === JSON_ERROR_NONE ? 'OK' : 'FAIL (error ' . $err . ')') . "\n";

if ($g) {
    echo "Type: " . $g->type . "\n";
}
