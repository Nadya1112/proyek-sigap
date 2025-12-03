<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$r = DB::table('kelurahans')->where('id', 2)->first();
$raw = $r->geometri;
$compact = preg_replace('/\s+/', '', $raw);
$g = @json_decode($compact, false);
$err = json_last_error();
echo $err === JSON_ERROR_NONE ? 'OK' : 'FAIL ' . $err;
