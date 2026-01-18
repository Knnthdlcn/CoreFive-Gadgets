<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Delete the user if exists
DB::table('users')->where('email', 'delicano123@gmail.com')->delete();

// Create the user fresh with correct password hash
$user = User::create([
    'first_name' => 'Alaisa',
    'last_name' => 'Guillena',
    'email' => 'delicano123@gmail.com',
    'password' => Hash::make('123123'),
    'contact' => '+1234567890',
    'address' => '123 Main Street, City',
    'role' => 'customer',
]);

echo "User recreated successfully!\n";
echo "Email: delicano123@gmail.com\n";
echo "Password: 123123\n";
echo "ID: {$user->id}\n";
