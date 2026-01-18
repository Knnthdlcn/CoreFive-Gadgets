<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Delete the user if exists
DB::table('users')->where('email', 'chai123@gmail.com')->delete();

// Create the user with correct password
$user = User::create([
    'first_name' => 'alaaaaaa',
    'last_name' => 'guilleena',
    'email' => 'chai123@gmail.com',
    'password' => Hash::make('123123123'),
    'contact' => '+1234567890',
    'address' => '123 Main Street, City',
    'role' => 'customer',
]);

echo "User chai123@gmail.com recreated successfully!\n";
echo "Email: chai123@gmail.com\n";
echo "Password: 123123123\n";
echo "ID: {$user->id}\n";
