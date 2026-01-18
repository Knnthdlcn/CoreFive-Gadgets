<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductStockController extends Controller
{
    public function show(Request $request, int $id): JsonResponse
    {
        $product = Product::where('product_id', $id)->firstOrFail();

        $variantId = $request->query('variant_id');
        if ($variantId !== null && $variantId !== '') {
            $variant = ProductVariant::where('id', (int) $variantId)
                ->where('product_id', $product->product_id)
                ->where('is_active', true)
                ->firstOrFail();

            return response()->json([
                'product_id' => $product->product_id,
                'variant_id' => $variant->id,
                'variant_name' => $variant->name,
                'price' => (float) $variant->effective_price,
                'unlimited' => (bool) $variant->stock_unlimited,
                'quantity' => (int) ($variant->stock ?? 0),
                'state' => $variant->stock_state,
                'display_text' => $variant->stock_display_text,
                'updated_at' => optional($product->stock_updated_at)->toIso8601String(),
            ]);
        }

        return response()->json([
            'product_id' => $product->product_id,
            // For variant products, report aggregate stock unless a specific variant_id is requested.
            'unlimited' => (bool) $product->effective_stock_unlimited,
            'quantity' => (int) ($product->effective_stock ?? 0),
            'state' => $product->stock_state,
            'display_text' => $product->stock_display_text,
            'updated_at' => optional($product->stock_updated_at)->toIso8601String(),
        ]);
    }
}
