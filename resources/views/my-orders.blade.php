@extends('layouts.app')

@section('content')
<div style="background: linear-gradient(135deg, #06131a 0%, #1a3a52 100%); min-height: 100vh; padding: 40px 0 30px;">
    <div class="container">
        <!-- Page Header -->
        <div class="row mb-3">
            <div class="col-12">
                <h1 style="color: #fff; font-weight: 700; font-size: 1.75rem; margin-bottom: 5px;">
                    <i class="fas fa-shopping-bag me-2" style="color: #ffc107;"></i>My Orders
                </h1>
                <p style="color: #b0c4de; font-size: 0.9rem;">Track and manage all your purchases</p>
            </div>
        </div>

        @if($orders->isEmpty())
            <!-- Empty State -->
            <div class="row">
                <div class="col-lg-6 mx-auto">
                    <div style="background: rgba(255, 255, 255, 0.95); border-radius: 12px; padding: 40px 30px; text-align: center; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);">
                        <i class="fas fa-inbox" style="font-size: 3rem; color: #ffc107; margin-bottom: 15px; display: block; opacity: 0.7;"></i>
                        <h4 style="color: #06131a; font-weight: 600; margin-bottom: 8px;">No Orders Yet</h4>
                        <p style="color: #666; margin-bottom: 25px; font-size: 0.95rem;">You haven't placed any orders yet. Start shopping to see your orders here!</p>
                        <a href="{{ route('home') }}" class="btn" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); border: none; color: #222; font-weight: 600; padding: 10px 30px; border-radius: 25px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);">
                            <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                        </a>
                    </div>
                </div>
            </div>
        @else
            <!-- Orders Grid -->
            <div class="row">
                @foreach($orders as $order)
                    <div class="col-lg-6 mb-3">
                        <div style="background: rgba(255, 255, 255, 0.97); border-radius: 8px; overflow: hidden; box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1); transition: all 0.3s ease; border-left: 3px solid #ffc107;">
                            <!-- Order Header -->
                            <div style="background: linear-gradient(135deg, #06131a 0%, #1a3a52 100%); padding: 12px 15px; color: #fff;">
                                <div class="row align-items-center">
                                    <div class="col-8">
                                        <h6 style="margin: 0; font-weight: 600; font-size: 0.95rem;">
                                            <i class="fas fa-receipt me-1" style="color: #ffc107;"></i>Order #{{ $order->id }}
                                        </h6>
                                        <p style="margin: 3px 0 0 0; color: #b0c4de; font-size: 0.75rem;">
                                            <i class="fas fa-calendar me-1"></i>{{ $order->created_at->format('M d, Y h:i A') }}
                                        </p>
                                    </div>
                                    <div class="col-4 text-end">
                                        <span style="display: inline-block; padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 0.75rem;
                                            @if($order->status === 'pending')
                                                background: #fff3cd; color: #856404;
                                            @elseif($order->status === 'completed')
                                                background: #d4edda; color: #155724;
                                            @else
                                                background: #f8d7da; color: #721c24;
                                            @endif
                                        ">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Details -->
                            <div style="padding: 15px;">
                                <div class="row mb-2">
                                    <div class="col-12 mb-2">
                                        <p style="color: #06131a; font-weight: 600; margin-bottom: 5px; font-size: 0.75rem;">
                                            <i class="fas fa-map-marker-alt me-1" style="color: #ffc107;"></i>SHIPPING ADDRESS
                                        </p>
                                        <p style="color: #333; margin: 0; line-height: 1.4; font-size: 0.85rem;">{{ $order->shipping_address }}</p>
                                    </div>
                                    <div class="col-12">
                                        <div style="background: rgba(255, 193, 7, 0.1); padding: 10px; border-radius: 6px; border-left: 2px solid #ffc107;">
                                            <p style="color: #06131a; font-weight: 600; margin-bottom: 4px; font-size: 0.75rem;">
                                                <i class="fas fa-truck me-1" style="color: #ffc107;"></i>{{ ucfirst($order->shipping_method) }}
                                            </p>
                                            <p style="color: #06131a; font-weight: 600; margin-bottom: 4px; font-size: 0.75rem;">
                                                <i class="fas fa-credit-card me-1" style="color: #ffc107;"></i>{{ ucfirst($order->payment_method) }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Items Table -->
                                <div style="margin: 15px 0; border-top: 1px solid #eee; padding-top: 12px;">
                                    <h6 style="color: #06131a; font-weight: 600; margin-bottom: 8px; font-size: 0.85rem;">
                                        <i class="fas fa-box me-1" style="color: #ffc107;"></i>Order Items
                                    </h6>
                                    <table style="width: 100%; border-collapse: collapse; font-size: 0.8rem;">
                                        <thead>
                                            <tr style="border-bottom: 2px solid #ffc107; background: rgba(255, 193, 7, 0.1);">
                                                <th style="padding: 8px 6px; text-align: left; color: #06131a; font-weight: 600; font-size: 0.75rem;">Product</th>
                                                <th style="padding: 8px 6px; text-align: right; color: #06131a; font-weight: 600; font-size: 0.75rem;">Price</th>
                                                <th style="padding: 8px 6px; text-align: center; color: #06131a; font-weight: 600; font-size: 0.75rem;">Qty</th>
                                                <th style="padding: 8px 6px; text-align: right; color: #06131a; font-weight: 600; font-size: 0.75rem;">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->items as $item)
                                                <tr style="border-bottom: 1px solid #eee;">
                                                    <td style="padding: 8px 6px; color: #333; font-size: 0.8rem;">
                                                        <div style="font-weight: 600;">{{ $item->product->product_name ?? 'Product' }}</div>
                                                        @if(!empty($item->variant_name))
                                                            <div style="color: #6c757d; font-size: 0.75rem;">Variant: {{ $item->variant_name }}</div>
                                                        @endif
                                                    </td>
                                                    <td style="padding: 8px 6px; color: #06131a; font-weight: 500; text-align: right;">₱{{ number_format($item->price, 2) }}</td>
                                                    <td style="padding: 8px 6px; color: #333; text-align: center; font-weight: 500;">{{ $item->quantity }}</td>
                                                    <td style="padding: 8px 6px; color: #06131a; font-weight: 600; text-align: right;">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Order Summary -->
                                <div style="background: linear-gradient(135deg, rgba(6, 19, 26, 0.05) 0%, rgba(26, 58, 82, 0.05) 100%); padding: 12px; border-radius: 6px; border: 1px solid #ffc107; margin-top: 12px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; padding-bottom: 6px; border-bottom: 1px solid #ddd;">
                                        <span style="color: #333; font-weight: 500; font-size: 0.8rem;">Subtotal:</span>
                                        <span style="color: #06131a; font-weight: 600; font-size: 0.85rem;">₱{{ number_format($order->subtotal, 2) }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #ddd;">
                                        <span style="color: #333; font-weight: 500; font-size: 0.8rem;">Shipping Cost:</span>
                                        <span style="color: #06131a; font-weight: 600; font-size: 0.85rem;">₱{{ number_format($order->shipping_fee, 2) }}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <span style="color: #06131a; font-weight: 700; font-size: 0.9rem;">Total Amount:</span>
                                        <span style="color: #ffc107; font-weight: 700; font-size: 1.05rem;">₱{{ number_format($order->total, 2) }}</span>
                                    </div>
                                </div>

                                <!-- Order Notes -->
                                @if($order->order_notes)
                                    <div style="margin-top: 12px; padding: 10px; background: #f8f9fa; border-radius: 6px; border-left: 2px solid #ffc107;">
                                        <p style="color: #06131a; font-weight: 600; margin-bottom: 5px; font-size: 0.75rem;">
                                            <i class="fas fa-sticky-note me-1" style="color: #ffc107;"></i>ORDER NOTES
                                        </p>
                                        <p style="color: #555; margin: 0; line-height: 1.4; font-size: 0.8rem;">{{ $order->order_notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    .col-lg-6 > div {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .col-lg-6 > div:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15) !important;
    }

    /* Animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .col-lg-6 {
        animation: slideInUp 0.5s ease-out forwards;
    }

    .col-lg-6:nth-child(1) { animation-delay: 0.05s; }
    .col-lg-6:nth-child(2) { animation-delay: 0.1s; }
    .col-lg-6:nth-child(3) { animation-delay: 0.15s; }
    .col-lg-6:nth-child(4) { animation-delay: 0.2s; }

    /* Responsive */
    @media (max-width: 991px) {
        .col-lg-6 {
            max-width: 100%;
        }
    }

    @media (max-width: 768px) {
        h1 {
            font-size: 1.4rem !important;
        }

        table {
            font-size: 0.75rem !important;
        }
    }
</style>
@endsection
