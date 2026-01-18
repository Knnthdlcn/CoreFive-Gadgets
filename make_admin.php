<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Update delicano123@gmail.com to have admin role
DB::table('users')->where('email', 'delicano123@gmail.com')->update(['role' => 'admin']);

echo "User delicano123@gmail.com has been upgraded to admin role!\n";
echo "You can now access the admin dashboard at: /admin/dashboard\n";
