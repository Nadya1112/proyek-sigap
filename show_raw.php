<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use Illuminate\Support\Facades\DB;
$id = $argv[1] ?? 1;
$r = DB::table('kelurahans')->where('id',$id)->first();
$raw = $r->geometri;
echo "ID: $id, Name: {$r->nama_kelurahan}\n";
echo "LEN: " . strlen($raw) . "\n";
echo "PREVIEW:\n" . substr($raw,0,300) . "\n\n";
$bytes = array_map('ord', str_split(substr($raw,0,10)));
echo "BYTES: " . implode(' ', $bytes) . "\n";

// show escaped characters
$escaped = '';
for ($i=0;$i<min(200,strlen($raw));$i++) {
    $c = $raw[$i];
    $escaped .= '\\x'.strtoupper(dechex(ord($c)));
}
echo "ESCAPED (200): $escaped\n";
