<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = DB::table('users')->select('id', 'email', 'role')->get();
echo "Current users in database:\n";
foreach($users as $u) {
    echo "ID {$u->id}: {$u->email} (role: {$u->role})\n";
}
