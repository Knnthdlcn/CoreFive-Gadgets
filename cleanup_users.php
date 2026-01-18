<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Delete John Doe and Jane Smith
DB::table('users')->where('email', 'john@example.com')->delete();
DB::table('users')->where('email', 'jane@example.com')->delete();

// Make delicano123@gmail.com admin
DB::table('users')->where('email', 'delicano123@gmail.com')->update(['role' => 'admin']);

echo "✓ Deleted: john@example.com\n";
echo "✓ Deleted: jane@example.com\n";
echo "✓ Made delicano123@gmail.com as admin\n";
