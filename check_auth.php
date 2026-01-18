<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$user = DB::table('users')->where('email', 'delicano123@gmail.com')->first();
if($user) {
    echo "User found:\n";
    echo "ID: {$user->id}\n";
    echo "Email: {$user->email}\n";
    echo "Password hash: {$user->password}\n\n";
    
    // Test if password matches
    $testPassword = '123123';
    if(Hash::check($testPassword, $user->password)) {
        echo "✓ Password '123123' matches the hash\n";
    } else {
        echo "✗ Password '123123' does NOT match the hash\n";
    }
} else {
    echo "User not found\n";
}
