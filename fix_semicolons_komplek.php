<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== Fixing Semicolons in Kompleks Table ===\n\n";

try {
    // Kolom yang akan diupdate
    $columns = ['nama_komplek', 'nama_pengembang', 'alamat', 'ibadah', 'umum', 'pendidikan', 'kesehatan'];
    
    foreach ($columns as $column) {
        // Cek apakah kolom ada
        $exists = DB::getSchemaBuilder()->hasColumn('kompleks', $column);
        
        if (!$exists) {
            echo "⚠ Kolom '$column' tidak ada di tabel kompleks, skip.\n";
            continue;
        }
        
        // Update kolom dengan mengganti ; dengan .
        $updated = DB::table('kompleks')
            ->where($column, 'like', '%;%')
            ->update([
                $column => DB::raw("REPLACE($column, ';', '.')")
            ]);
        
        if ($updated > 0) {
            echo "✓ Kolom '$column': $updated record(s) updated\n";
        } else {
            echo "○ Kolom '$column': tidak ada perubahan\n";
        }
    }
    
    echo "\n=== Done! ===\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
