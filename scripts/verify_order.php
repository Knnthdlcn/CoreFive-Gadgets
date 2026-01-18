<?php

/**
 * Usage:
 *   php scripts/verify_order.php <orderId>
 */

$basePath = dirname(__DIR__);

require $basePath . '/vendor/autoload.php';

$app = require $basePath . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$orderId = isset($argv[1]) ? (int) $argv[1] : 0;
if ($orderId <= 0) {
    fwrite(STDERR, "Provide orderId.\n");
    exit(1);
}

$order = App\Models\Order::with('items')->find($orderId);
if (!$order) {
    fwrite(STDERR, "Order not found: {$orderId}\n");
    exit(1);
}

echo "order_id={$order->id} subtotal={$order->subtotal} shipping_fee={$order->shipping_fee} total={$order->total}" . PHP_EOL;
foreach ($order->items as $it) {
    $variantId = $it->product_variant_id === null ? 'null' : (string) (int) $it->product_variant_id;
    echo "item_id={$it->id} product_id={$it->product_id} variant_id={$variantId} qty={$it->quantity} price={$it->price}" . PHP_EOL;
}

$first = $order->items->first();
if ($first) {
    $product = App\Models\Product::where('product_id', (int) $first->product_id)->first();
    if ($product) {
        echo "product_stock={$product->stock}" . PHP_EOL;
    }
}
