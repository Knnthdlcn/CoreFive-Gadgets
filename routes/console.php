<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:sync-google {email : The Gmail address to use for admin}', function (string $email) {
    $email = trim(strtolower($email));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $this->error('Invalid email.');
        return 1;
    }

    $user = User::where('email', $email)->first();

    if (!$user) {
        $this->warn('No user found with that email. Creating one as admin (password: 123123).');
        $user = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => $email,
            'password' => Hash::make('123123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }

    $user->role = 'admin';
    if (empty($user->email_verified_at)) {
        $user->email_verified_at = now();
    }
    $user->save();

    $this->info('Admin synced to: ' . $user->email . ' (user id: ' . $user->id . ')');
    return 0;
})->purpose('Promote or create an admin user using your Gmail address');

Artisan::command('products:import-images {--apply : Actually insert rows} {--dir=images : Directory under public/} {--default-stock=25 : Stock quantity for new products}', function () {
    $publicRelDir = (string) $this->option('dir');
    $absoluteDir = public_path($publicRelDir);

    if (!is_dir($absoluteDir)) {
        $this->error('Directory not found: ' . $absoluteDir);
        return 1;
    }

    $apply = (bool) $this->option('apply');
    $defaultStock = (int) $this->option('default-stock');
    if ($defaultStock < 0) {
        $defaultStock = 0;
    }

    $files = File::files($absoluteDir);
    $candidates = [];

    foreach ($files as $file) {
        $filename = $file->getFilename();

        // Only import files that look like uploaded product images: "<timestamp>_<name>.jpg"
        if (!preg_match('/^\d+_(.+)\.(jpe?g|png|webp)$/i', $filename, $m)) {
            continue;
        }

        $namePart = $m[1];
        $raw = strtolower($namePart);

        $category = 'Accessories';
        $min = 499;
        $max = 24999;

        $isPhone = str_contains($raw, 'iphone') || str_contains($raw, 'samsung') || str_contains($raw, 'pixel');
        $isCamera = str_contains($raw, 'canon') || str_contains($raw, 'sony') || str_contains($raw, 'nikon') || str_contains($raw, 'fujifilm') || str_contains($raw, 'insta360') || str_contains($raw, 'gopro') || str_contains($raw, 'lens');
        $isDrone = str_contains($raw, 'dji') || str_contains($raw, 'mavic') || str_contains($raw, 'mini-') || str_contains($raw, 'avata') || str_contains($raw, 'air3') || str_contains($raw, 'rs4');

        if ($isPhone) {
            $category = 'Phones';
            $min = 19999;
            $max = 149999;
        } elseif ($isDrone || $isCamera) {
            $category = 'Cameras';
            $min = 4999;
            $max = 249999;
        }

        $seed = abs((int) crc32($filename));
        $price = $min + ($seed % max(1, ($max - $min + 1)));

        $productName = Str::of($namePart)
            ->replace(['_', '-'], ' ')
            ->squish()
            ->title()
            ->toString();

        $imagePath = trim($publicRelDir, '/\\') . '/' . $filename;
        $imagePath = str_replace('\\', '/', $imagePath);

        $candidates[] = [
            'product_name' => $productName,
            'category' => $category,
            'price' => (float) $price,
            'image_path' => $imagePath,
            'stock' => $defaultStock,
        ];
    }

    if (empty($candidates)) {
        $this->warn('No matching images found to import.');
        return 0;
    }

    $created = 0;
    $skipped = 0;

    foreach ($candidates as $row) {
        $exists = Product::where('image_path', $row['image_path'])->exists();
        if ($exists) {
            $skipped++;
            continue;
        }

        if ($apply) {
            Product::create([
                'product_name' => $row['product_name'],
                'category' => $row['category'],
                'description' => 'Imported product',
                'price' => $row['price'],
                'image_path' => $row['image_path'],
                'stock' => $row['stock'],
            ]);
        }

        $created++;
    }

    if ($apply) {
        $this->info("Imported products: {$created} (skipped existing: {$skipped})");
    } else {
        $this->info("Dry run: would import {$created} products (skipped existing: {$skipped}). Re-run with --apply to insert.");
    }

    return 0;
})->purpose('Import product rows from uploaded images in public/');

Artisan::command('orders:fix-qty {orderId : The order ID to repair} {--qty= : The corrected quantity (>=1)} {--item_id= : Specific order_items.id to update (required if order has multiple items)}', function (int $orderId) {
    $newQty = (int) ($this->option('qty') ?? 0);
    if ($newQty < 1) {
        $this->error('Provide a valid --qty (>= 1). Example: php artisan orders:fix-qty 50 --qty=4');
        return 1;
    }

    $itemId = $this->option('item_id');
    $itemId = ($itemId === null || $itemId === '') ? null : (int) $itemId;

    $order = Order::with(['items'])->find($orderId);
    if (!$order) {
        $this->error('Order not found: ' . $orderId);
        return 1;
    }

    if ($order->items->isEmpty()) {
        $this->error('Order has no items to repair.');
        return 1;
    }

    if ($order->items->count() > 1 && !$itemId) {
        $this->error('Order has multiple items. Provide --item_id=<order_items.id> to update a specific line.');
        $this->line('Items:');
        foreach ($order->items as $it) {
            $this->line(sprintf(' - item_id=%d product_id=%d variant_id=%s qty=%d price=%0.2f',
                (int) $it->id,
                (int) $it->product_id,
                $it->product_variant_id === null ? 'null' : (string) (int) $it->product_variant_id,
                (int) $it->quantity,
                (float) $it->price
            ));
        }
        return 1;
    }

    /** @var OrderItem $target */
    $target = $itemId
        ? $order->items->firstWhere('id', $itemId)
        : $order->items->first();

    if (!$target) {
        $this->error('Order item not found on this order: ' . (string) $itemId);
        return 1;
    }

    $oldQty = (int) $target->quantity;
    $delta = $newQty - $oldQty;

    if ($delta === 0) {
        $this->info('No change needed (quantity already ' . $newQty . ').');
        return 0;
    }

    DB::transaction(function () use ($order, $target, $newQty, $delta) {
        // Lock rows for safe stock/totals adjustments
        $order = Order::where('id', $order->id)->lockForUpdate()->firstOrFail();
        $item = OrderItem::where('id', $target->id)->where('order_id', $order->id)->lockForUpdate()->firstOrFail();

        $variant = null;
        if ($item->product_variant_id !== null) {
            $variant = ProductVariant::where('id', (int) $item->product_variant_id)->lockForUpdate()->first();
        }
        $product = Product::where('product_id', (int) $item->product_id)->lockForUpdate()->firstOrFail();

        // Adjust stock by delta
        if ($delta !== 0) {
            if ($variant) {
                if (!(bool) ($variant->stock_unlimited ?? false)) {
                    $newStock = (int) ($variant->stock ?? 0) - $delta;
                    if ($newStock < 0) {
                        throw new RuntimeException('Variant stock would go negative. Aborting.');
                    }
                    $variant->stock = $newStock;
                    $variant->save();
                }
            } else {
                if (!(bool) ($product->stock_unlimited ?? false)) {
                    $newStock = (int) ($product->stock ?? 0) - $delta;
                    if ($newStock < 0) {
                        throw new RuntimeException('Product stock would go negative. Aborting.');
                    }
                    $product->stock = $newStock;
                    $product->stock_updated_at = now();
                    $product->save();
                }
            }
        }

        // Update the item qty
        $item->quantity = $newQty;
        $item->save();

        // Recalculate order totals from items
        $freshItems = OrderItem::where('order_id', $order->id)->get();
        $subtotal = $freshItems->sum(fn ($i) => (float) $i->price * (int) $i->quantity);
        $shippingFee = (float) ($order->shipping_fee ?? 0);
        $order->subtotal = $subtotal;
        $order->total = $subtotal + $shippingFee;
        $order->save();
    });

    $this->info(sprintf(
        'Repaired order %d item %d: qty %d -> %d (delta %+d).',
        $orderId,
        (int) $target->id,
        $oldQty,
        $newQty,
        $delta
    ));

    return 0;
})->purpose('Repair an order item quantity and adjust stock/totals accordingly (useful when a bad checkout saved qty=1)');
