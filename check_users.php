<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$users = DB::table('users')->select('id', 'first_name', 'last_name', 'email', 'password')->get();
foreach($users as $u) {
    echo "id={$u->id}: {$u->first_name} {$u->last_name} ({$u->email})\n";
    echo "  Hash: " . substr($u->password, 0, 20) . "...\n";
}
