<?php
$j = json_decode(file_get_contents(__DIR__.'/tmp_kelurahan_response.json'), true);
if (!isset($j['geojson']['features'])) {
    echo "NO_FEATURES\n";
    exit(0);
}
echo count($j['geojson']['features']) . "\n";
// optionally list first 5 properties
for ($i=0;$i<min(5,count($j['geojson']['features']));$i++){
    $p = $j['geojson']['features'][$i]['properties'] ?? [];
    echo ($i+1) . ". " . ($p['nama_kelurahan'] ?? '-') . " (" . ($p['nama_kecamatan'] ?? '-') . ")\n";
}
