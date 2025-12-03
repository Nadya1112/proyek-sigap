<?php
// Quick check: find user by email and show basic info (no secrets)
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$email = 'hanifabdurrahman@gmail.com';
$u = User::where('email', $email)->first();
if (!$u) {
    echo "User not found for email: $email\n";
    exit(1);
}

echo "Found user:\n";
echo "- id: {$u->id}\n";
echo "- name: {$u->name}\n";
echo "- email: {$u->email}\n";
echo "- role: " . ($u->role ?? '(none)') . "\n";
echo "- email_verified_at: " . ($u->email_verified_at ?? '(null)') . "\n";
echo "- created_at: {$u->created_at}\n";
echo "- remember_token: " . ($u->remember_token ? 'present' : '(null)') . "\n";
// Do not print full password hash; show prefix only
if (isset($u->password)) {
    echo "- password hash (prefix): " . substr($u->password,0,10) . "...\n";
}

// Show last successful/failed login meta if available (assuming there is last_login_at)
if (isset($u->last_login_at)) echo "- last_login_at: {$u->last_login_at}\n";

// Optionally show related komplek_id or other fields if present
foreach (['kompleks_id','kelurahan_id','kecamatan_id'] as $f) {
    if (isset($u->$f)) echo "- $f: {$u->$f}\n";
}

// Exit
exit(0);
