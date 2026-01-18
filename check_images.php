<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$products = DB::table('products')->select('product_name', 'image_path')->get();
foreach($products as $p) {
    echo $p->product_name . ' => ' . $p->image_path . PHP_EOL;
}
