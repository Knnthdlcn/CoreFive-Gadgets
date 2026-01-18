<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "Updating product categories...\n";

DB::table('products')->update([
    'category' => DB::raw("CASE 
        WHEN product_name LIKE '%iPhone%' OR product_name LIKE '%Phone%' OR product_name LIKE '%Galaxy%' OR product_name LIKE '%Pixel%' THEN 'Phones'
        WHEN product_name LIKE '%MacBook%' OR product_name LIKE '%Laptop%' OR product_name LIKE '%Computer%' OR product_name LIKE '%PC%' THEN 'Computing'
        ELSE 'Accessories'
    END")
]);

echo "Categories updated successfully!\n";

// Display updated products
$products = DB::table('products')->select('product_id', 'product_name', 'category')->get();
foreach ($products as $product) {
    echo "{$product->product_id}: {$product->product_name} -> {$product->category}\n";
}
