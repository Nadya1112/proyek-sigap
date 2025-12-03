<?php
$path = __DIR__ . '/storage/logs/laravel.log';
$needle = 'hanifabdurrahman';
if (!file_exists($path)) { echo "Log file not found: $path\n"; exit(1); }
$fh = fopen($path, 'r');
if (!$fh) { echo "Cannot open log\n"; exit(1); }
while (($line = fgets($fh)) !== false) {
    if (stripos($line, $needle) !== false) echo $line;
}
fclose($fh);
echo "\nDone.\n";
