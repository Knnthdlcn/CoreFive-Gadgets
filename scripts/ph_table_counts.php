<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$tables = [
    'philippine_regions',
    'philippine_provinces',
    'philippine_cities',
    'philippine_barangays',
];

foreach ($tables as $table) {
    try {
        $count = DB::table($table)->count();
        echo $table . '=' . $count . PHP_EOL;
    } catch (Throwable $e) {
        echo $table . '=ERROR ' . $e->getMessage() . PHP_EOL;
    }
}
