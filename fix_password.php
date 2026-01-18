<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Hash the password using bcrypt
$hashedPassword = Hash::make('123123');

// Update the user
DB::table('users')->where('email', 'delicano123@gmail.com')->update([
    'password' => $hashedPassword
]);

echo "Password updated for delicano123@gmail.com\n";
echo "You can now login with:\n";
echo "Email: delicano123@gmail.com\n";
echo "Password: 123123\n";
