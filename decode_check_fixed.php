<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('kelurahans')->select('id','nama_kelurahan','geometri')->orderBy('id')->get();
$ok = 0; $i=0;
foreach ($rows as $r) {
    $i++;
    $raw = $r->geometri;
    if (is_string($raw)) {
        $raw = trim($raw, '"');
        $raw = stripslashes($raw);
        $raw = preg_replace('/\s+/', '', $raw); // <-- NEW: Remove all whitespace
    }
    $geo = @json_decode($raw, false);
    $err = json_last_error();
    echo sprintf("%2d | %-28s | err=%d | type=%s\n", $r->id, $r->nama_kelurahan, $err, ($geo->type ?? 'NULL'));
    if ($err === JSON_ERROR_NONE) $ok++;
}
echo "\nTOTAL OK: $ok / $i\n";
