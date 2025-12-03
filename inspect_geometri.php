<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$rows = DB::table('kelurahans')->select('id','nama_kelurahan','geometri')->orderBy('id')->get();
foreach ($rows as $r) {
    $g = $r->geometri;
    $len = is_null($g)?0:strlen($g);
    $preview = is_null($g)?'NULL':substr(trim($g),0,120);
    echo sprintf("%3d | %-28s | len=%4d | %s\n", $r->id, $r->nama_kelurahan, $len, str_replace("\n"," ", $preview));
}
