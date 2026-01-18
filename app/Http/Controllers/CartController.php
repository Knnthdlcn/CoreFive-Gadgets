<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Exceptions\HttpResponseException;

class CartController extends Controller
{
    private function hasActiveVariants(Product $product): bool
    {
        return $product->variants()->where('is_active', true)->exists();
    }

    private function resolveVariantOrFail(Product $product, ?int $variantId): ?ProductVariant
    {
        $needsVariant = $this->hasActiveVariants($product);
        if (!$needsVariant) {
            return null;
        }

        if (!$variantId) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Please select a variant for this product.',
            ], 422));
        }

        $variant = ProductVariant::where('id', $variantId)
            ->where('product_id', $product->product_id)
            ->where('is_active', true)
            ->first();

        if (!$variant) {
            throw new HttpResponseException(response()->json([
                'success' => false,
                'message' => 'Selected variant is not available.',
            ], 404));
        }

        return $variant;
    }

    public function index(): View
    {
        $cartItems = [];
        $total = 0;

        if (Auth::check()) {
            // Get cart from database for logged-in users
            $dbCartItems = CartItem::where('user_id', Auth::id())
                ->with(['product', 'variant', 'product.variants'])
                ->get();

            foreach ($dbCartItems as $item) {
                if ($item->product) {
                    $unitPrice = (float) ($item->variant?->effective_price ?? $item->product->price);
                    $lineTotal = $unitPrice * (int) $item->quantity;

                    $stockUnlimited = (bool) ($item->variant?->stock_unlimited ?? $item->product->stock_unlimited ?? false);
                    $stockQty = (int) ($item->variant?->stock ?? $item->product->stock ?? 0);

                    $cartItems[] = [
                        'product' => $item->product,
                        'variant' => $item->variant,
                        'cart_item_id' => $item->id,
                        'product_variant_id' => $item->product_variant_id,
                        'variant_name' => $item->variant?->name,
                        'unit_price' => $unitPrice,
                        'quantity' => $item->quantity
                    ];
                    $total += $lineTotal;
                }
            }
        } else {
            // Get cart from session for guests
            $cart = session()->get('cart', []);
            foreach ($cart as $key => $details) {
                $productId = (int) ($details['product_id'] ?? 0);
                $variantId = isset($details['product_variant_id']) ? (int) $details['product_variant_id'] : null;

                $product = Product::where('product_id', $productId)->first();
                if ($product) {
                    $variant = null;
                    if ($variantId) {
                        $variant = ProductVariant::where('id', $variantId)
                            ->where('product_id', $product->product_id)
                            ->where('is_active', true)
                            ->first();
                    }
                    $unitPrice = (float) ($variant?->effective_price ?? $product->price);
                    $qty = (int) ($details['quantity'] ?? 1);

                    $cartItems[] = [
                        'product' => $product,
                        'variant' => $variant,
                        'cart_item_id' => null,
                        'product_variant_id' => $variant?->id,
                        'variant_name' => $variant?->name,
                        'unit_price' => $unitPrice,
                        'quantity' => $qty
                    ];
                    $total += $unitPrice * $qty;
                }
            }
        }

        return view('cart', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'requires_login' => true,
                'message' => 'Please log in to add items to your cart.',
            ], 401);
        }

        $productId = $request->input('product_id');
        $variantId = $request->input('product_variant_id');
        $quantity = max(1, (int) $request->input('quantity', 1));

        $product = Product::where('product_id', $productId)->first();

        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $variant = $this->resolveVariantOrFail($product, $variantId !== null ? (int) $variantId : null);

        $isUnlimited = (bool) ($variant?->stock_unlimited ?? $product->stock_unlimited ?? false);
        $available = (int) ($variant?->stock ?? $product->stock ?? 0);
        if (!$isUnlimited && $available <= 0) {
            return response()->json(['success' => false, 'message' => 'Out of stock'], 409);
        }

        if (Auth::check()) {
            // Save to database for logged-in users
            $cartItemQuery = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId);
            if ($variant?->id) {
                $cartItemQuery->where('product_variant_id', $variant->id);
            } else {
                $cartItemQuery->whereNull('product_variant_id');
            }
            $cartItem = $cartItemQuery->first();

            if ($cartItem) {
                $newQty = (int) $cartItem->quantity + $quantity;
                if (!$isUnlimited && $newQty > $available) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Only ' . $available . ' left in stock',
                    ], 409);
                }
                $cartItem->quantity = $newQty;
                $cartItem->save();
            } else {
                if (!$isUnlimited && $quantity > $available) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Only ' . $available . ' left in stock',
                    ], 409);
                }
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'product_variant_id' => $variant?->id,
                    'quantity' => $quantity
                ]);
            }

            $cartCount = CartItem::where('user_id', Auth::id())->count();
        } else {
            // Should never happen (guarded above), but keep a safe fallback
            return response()->json([
                'success' => false,
                'requires_login' => true,
                'message' => 'Please log in to add items to your cart.',
            ], 401);
        }

        return response()->json([
            'success' => true, 
            'message' => 'Product added to cart!',
            'cart_count' => $cartCount
        ]);
    }

    public function update(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'requires_login' => true,
                'message' => 'Please log in to update your cart.',
            ], 401);
        }

        $productId = $request->input('product_id');
        $variantId = $request->input('product_variant_id');
        $quantity = (int) $request->input('quantity');

        $product = Product::where('product_id', $productId)->first();
        if (!$product) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        }

        $variant = $this->resolveVariantOrFail($product, $variantId !== null ? (int) $variantId : null);

        $isUnlimited = (bool) ($variant?->stock_unlimited ?? $product->stock_unlimited ?? false);
        $available = (int) ($variant?->stock ?? $product->stock ?? 0);
        if ($quantity > 0 && !$isUnlimited) {
            if ($available <= 0) {
                return response()->json(['success' => false, 'message' => 'Out of stock'], 409);
            }
            if ($quantity > $available) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only ' . $available . ' left in stock',
                ], 409);
            }
        }

        if (Auth::check()) {
            // Update database for logged-in users
            $cartItemQuery = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId);
            if ($variant?->id) {
                $cartItemQuery->where('product_variant_id', $variant->id);
            } else {
                $cartItemQuery->whereNull('product_variant_id');
            }
            $cartItem = $cartItemQuery->first();

            if ($cartItem) {
                if ($quantity > 0) {
                    $cartItem->quantity = $quantity;
                    $cartItem->save();
                    return response()->json(['success' => true, 'message' => 'Cart updated']);
                } else {
                    $cartItem->delete();
                    return response()->json(['success' => true, 'message' => 'Item removed from cart']);
                }
            }
        } else {
            // Update session for guests
            $cart = session()->get('cart', []);

            $key = (string) $productId . ':' . (string) ($variant?->id ?? 0);

            if (isset($cart[$key])) {
                if ($quantity > 0) {
                    $cart[$key]['quantity'] = $quantity;
                    session()->put('cart', $cart);
                    return response()->json(['success' => true, 'message' => 'Cart updated']);
                } else {
                    unset($cart[$key]);
                    session()->put('cart', $cart);
                    return response()->json(['success' => true, 'message' => 'Item removed from cart']);
                }
            }
        }

        return response()->json(['success' => false, 'message' => 'Item not found in cart'], 404);
    }

    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        $variantId = $request->input('product_variant_id');

        if (Auth::check()) {
            // Remove from database for logged-in users
            $cartItemQuery = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId);
            if ($variantId) {
                $cartItemQuery->where('product_variant_id', (int) $variantId);
            } else {
                $cartItemQuery->whereNull('product_variant_id');
            }
            $cartItem = $cartItemQuery->first();

            if ($cartItem) {
                $cartItem->delete();
                $cartCount = CartItem::where('user_id', Auth::id())->count();
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Product removed from cart',
                    'cart_count' => $cartCount
                ]);
            }
        } else {
            // Remove from session for guests
            $cart = session()->get('cart', []);

            $key = (string) $productId . ':' . (string) ((int) ($variantId ?? 0));
            if (isset($cart[$key])) {
                unset($cart[$key]);
                session()->put('cart', $cart);
                
                return response()->json([
                    'success' => true, 
                    'message' => 'Product removed from cart',
                    'cart_count' => count($cart)
                ]);
            }
        }

        return response()->json(['success' => false, 'message' => 'Item not found in cart'], 404);
    }

    public function clear()
    {
        if (Auth::check()) {
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }
        
        return response()->json(['success' => true, 'message' => 'Cart cleared']);
    }

    public function getCart(Request $request): \Illuminate\Http\JsonResponse
    {
        if (Auth::check()) {
            $cartCount = CartItem::where('user_id', Auth::id())->count();
        } else {
            $cart = session()->get('cart', []);
            $cartCount = count($cart);
        }

        return response()->json([
            'success' => true,
            'cart_count' => $cartCount
        ]);
    }
}
