<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

$products = DB::table('products')->get();

foreach ($products as $product) {
    $newImagePath = str_replace(
        'via.placeholder.com/300',
        'placehold.co/300x300/FFC107/000000',
        $product->image_path
    );
    
    DB::table('products')
        ->where('product_id', $product->product_id)
        ->update(['image_path' => $newImagePath]);
    
    echo "Updated {$product->product_name}: {$newImagePath}\n";
}

echo "\nAll product images updated successfully!\n";
