@extends('layouts.app')

@section('title', 'E-Shop - Welcome')

@section('content')
    <!-- Error Message Modal Bubble -->
    @if(session('error'))
        <div class="alert notification-modal-container" style="position: fixed; top: 80px; left: 50%; transform: translateX(-50%); z-index: 1050; width: 90%; max-width: 500px; animation: slideDown 0.4s cubic-bezier(0.34, 1.56, 0.64, 1); border: none; background: transparent; padding: 0; margin: 0;">
            <div class="notification-bubble" style="background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%); border-radius: 20px; padding: 28px; box-shadow: 0 10px 40px rgba(13, 71, 161, 0.3); position: relative; overflow: hidden;">
                <!-- Background accent -->
                <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(255, 193, 7, 0.15); border-radius: 50%;"></div>
                
                <div class="d-flex align-items-flex-start gap-3" style="position: relative; z-index: 1;">
                    <!-- Icon -->
                    <div style="flex-shrink: 0; padding-top: 4px;">
                        <i class="fas fa-lock-open" style="font-size: 2.5rem; color: #ffc107;"></i>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-grow-1">
                        <h5 style="margin: 0; color: white; font-weight: 700; font-size: 1.2rem;">Sign In Required</h5>
                        <p style="margin: 10px 0 0 0; color: rgba(255, 255, 255, 0.9); font-size: 0.95rem; line-height: 1.5;">{{ session('error') }}</p>
                    </div>
                    
                    <!-- Close button -->
                    <button type="button" class="close-bubble" data-bs-dismiss="alert" aria-label="Close" style="background: rgba(255, 193, 7, 0.2); border: 2px solid #ffc107; color: #ffc107; width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.3s ease; font-size: 1.2rem; padding: 0; flex-shrink: 0;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>

        <style>
            @keyframes slideDown {
                from {
                    opacity: 0;
                    transform: translateX(-50%) translateY(-30px);
                }
                to {
                    opacity: 1;
                    transform: translateX(-50%) translateY(0);
                }
            }

            .close-bubble:hover {
                background: #ffc107 !important;
                color: #1565c0 !important;
                box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
            }

            .notification-modal-container {
                animation: slideDown 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            }
        </style>
    @endif

    <!-- MOBILE (portrait) HOME UI -->
    <section class="d-lg-none" style="background: #f3f4f6;">
        <div class="container pt-3">
            <!-- Banner / carousel -->
            <div id="homePromoCarousel" class="carousel slide" data-bs-ride="carousel" style="border-radius: 16px; overflow: hidden;">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div style="height: 140px; background: linear-gradient(135deg, #06131a 0%, #1a3a52 100%); display:flex; align-items:center; padding: 18px; position: relative;">
                            <div style="position:absolute; top:-28px; right:-28px; width: 120px; height: 120px; border-radius: 999px; background: rgba(255, 193, 7, 0.16);"></div>
                            <div style="color:#fff; position: relative;">
                                <div style="font-weight: 900; font-size: 1.15rem;">CoreFive Picks</div>
                                <div style="opacity: 0.9; font-weight: 600;">Premium gadgets, great prices</div>
                                <a href="{{ route('products.index') }}" class="btn btn-sm mt-2" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); color:#222; font-weight:900; border-radius: 999px; border: 0;">Shop now</a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div style="height: 140px; background: linear-gradient(135deg, #0d47a1 0%, #1565c0 100%); display:flex; align-items:center; padding: 18px; position: relative;">
                            <div style="position:absolute; bottom:-34px; left:-34px; width: 140px; height: 140px; border-radius: 999px; background: rgba(255, 255, 255, 0.12);"></div>
                            <div style="color:#fff; position: relative;">
                                <div style="font-weight: 900; font-size: 1.15rem;">Fast Delivery</div>
                                <div style="opacity: 0.9; font-weight: 600;">Reliable service, smooth checkout</div>
                                <a href="{{ route('products.index') }}" class="btn btn-sm mt-2" style="background: rgba(255,255,255,0.92); color:#0b1220; font-weight:900; border-radius: 999px; border: 0;">Browse</a>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div style="height: 140px; background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); display:flex; align-items:center; padding: 18px; position: relative;">
                            <div style="position:absolute; top:-28px; left:-28px; width: 120px; height: 120px; border-radius: 999px; background: rgba(0, 0, 0, 0.12);"></div>
                            <div style="color:#222; position: relative;">
                                <div style="font-weight: 1000; font-size: 1.15rem;">Hot Deals</div>
                                <div style="opacity: 0.9; font-weight: 800;">Limited-time discounts</div>
                                <a href="{{ route('products.index') }}" class="btn btn-sm mt-2" style="background:#111827; color:#fff; font-weight:900; border-radius: 999px; border: 0;">See deals</a>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#homePromoCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#homePromoCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <!-- Quick actions (Shopee-like icon grid) -->
            <div class="mt-3" style="background:#fff; border-radius: 16px; padding: 14px; box-shadow: 0 8px 18px rgba(0,0,0,0.06);">
                <div class="row g-3 text-center">
                    <div class="col-3">
                        <a href="{{ route('products.index') }}" class="text-decoration-none" style="color:#111827;">
                            <div class="mx-auto" style="width: 44px; height: 44px; border-radius: 14px; background: rgba(255, 193, 7, 0.18); display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-store" style="color:#f59e0b;"></i>
                            </div>
                            <div class="mt-1" style="font-size: 0.78rem; font-weight: 700;">Shop</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('products.index') }}" class="text-decoration-none" style="color:#111827;">
                            <div class="mx-auto" style="width: 44px; height: 44px; border-radius: 14px; background: rgba(13, 71, 161, 0.12); display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-tags" style="color:#1565c0;"></i>
                            </div>
                            <div class="mt-1" style="font-size: 0.78rem; font-weight: 700;">Deals</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('cart.index') }}" class="text-decoration-none" style="color:#111827;">
                            <div class="mx-auto" style="width: 44px; height: 44px; border-radius: 14px; background: rgba(255, 193, 7, 0.16); display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-cart-shopping" style="color:#ff9800;"></i>
                            </div>
                            <div class="mt-1" style="font-size: 0.78rem; font-weight: 700;">Cart</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('contact.index') }}" class="text-decoration-none" style="color:#111827;">
                            <div class="mx-auto" style="width: 44px; height: 44px; border-radius: 14px; background: rgba(26, 58, 82, 0.14); display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-headset" style="color:#1a3a52;"></i>
                            </div>
                            <div class="mt-1" style="font-size: 0.78rem; font-weight: 700;">Support</div>
                        </a>
                    </div>

                    <div class="col-3">
                        <a href="{{ route('products.index') }}" class="text-decoration-none" style="color:#111827;">
                            <div class="mx-auto" style="width: 44px; height: 44px; border-radius: 14px; background: rgba(13, 71, 161, 0.10); display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-layer-group" style="color:#0d47a1;"></i>
                            </div>
                            <div class="mt-1" style="font-size: 0.78rem; font-weight: 700;">Categories</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('products.index') }}" class="text-decoration-none" style="color:#111827;">
                            <div class="mx-auto" style="width: 44px; height: 44px; border-radius: 14px; background: rgba(255, 193, 7, 0.16); display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-bolt" style="color:#ff9800;"></i>
                            </div>
                            <div class="mt-1" style="font-size: 0.78rem; font-weight: 700;">Flash</div>
                        </a>
                    </div>
                    <div class="col-3">
                        <a href="{{ route('orders.index') }}" class="text-decoration-none" style="color:#111827;">
                            <div class="mx-auto" style="width: 44px; height: 44px; border-radius: 14px; background: rgba(148, 163, 184, 0.18); display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-bag-shopping" style="color:#0f172a;"></i>
                            </div>
                            <div class="mt-1" style="font-size: 0.78rem; font-weight: 700;">Orders</div>
                        </a>
                    </div>
                    <div class="col-3">
                        @auth
                            <a href="{{ route('profile') }}" class="text-decoration-none" style="color:#111827;">
                                <div class="mx-auto" style="width: 44px; height: 44px; border-radius: 14px; background: rgba(255, 193, 7, 0.18); display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-user" style="color:#ff9800;"></i>
                                </div>
                                <div class="mt-1" style="font-size: 0.78rem; font-weight: 700;">Me</div>
                            </a>
                        @else
                            <a href="#" onclick="showLoginModal(); return false;" class="text-decoration-none" style="color:#111827;">
                                <div class="mx-auto" style="width: 44px; height: 44px; border-radius: 14px; background: rgba(255, 193, 7, 0.18); display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-right-to-bracket" style="color:#ff9800;"></i>
                                </div>
                                <div class="mt-1" style="font-size: 0.78rem; font-weight: 700;">Login</div>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Featured products (mobile section header) -->
            <div class="d-flex align-items-center justify-content-between mt-4 mb-2">
                <div style="font-weight: 900; color:#111827; font-size: 1.05rem;">Featured Products</div>
                <a href="{{ route('products.index') }}" style="text-decoration:none; font-weight: 900; color:#f59e0b;">See all <i class="fas fa-chevron-right" style="font-size: 0.8rem;"></i></a>
            </div>
        </div>
    </section>

    <!-- HERO (desktop) -->
    <header class="site-hero text-center text-white py-5 d-none d-lg-block" style="background: linear-gradient(135deg, #06131a 0%, #1a3a52 100%);">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Welcome to CoreFive Gadgets</h1>
            <p class="lead mb-4">Discover premium products for your business and lifestyle</p>
        </div>
    </header>

    <!-- PRODUCTS -->
    <section class="py-4 py-lg-5" id="featured-products" style="background: #f3f4f6;">
        <div class="container">
            <div class="d-none d-lg-block">
                <h2 class="text-center mb-2" style="font-size: 2.5rem; font-weight: 700;">Featured Products</h2>
                <p class="text-center text-muted mb-5">Handpicked gadgets and accessories for tech enthusiasts</p>
            </div>

            <div class="row g-3 g-lg-4">
                @forelse($products as $product)
                    <div class="col-6 col-md-4">
                        <div class="card h-100 border-0 shadow-sm product-card" style="transition: all 0.3s ease; border-radius: 14px; overflow: hidden; background: #ffffff;">
                            <a href="{{ route('product.show', $product->product_id) }}" class="text-decoration-none">
                                <div class="card-img-wrapper position-relative overflow-hidden" style="height: 190px; background: #ffffff; cursor: pointer; border: 1px solid #f0f0f0;">
                                    <img src="{{ $product->image_url }}" class="card-img-top w-100 h-100" alt="{{ $product->product_name }}" style="object-fit: contain; padding: 10px; transition: transform 0.3s ease;" onerror="this.src='{{ asset('images/placeholder.png') }}';">
                                    <div class="overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0, 0, 0, 0); transition: background 0.3s ease; pointer-events: none;">
                                        <span class="badge bg-dark text-white px-3 py-2" style="opacity: 0; transition: opacity 0.3s ease;">
                                            <i class="fas fa-eye me-1"></i> View Details
                                        </span>
                                    </div>
                                </div>
                            </a>
                            <div class="card-body d-flex flex-column p-3 p-lg-4" style="background: #ffffff;">
                                <a href="{{ route('product.show', $product->product_id) }}" class="text-decoration-none">
                                    <h5 class="card-title mb-2" style="font-weight: 700; font-size: 0.95rem; color: #111827; line-height: 1.2;">
                                        {{ $product->product_name }}
                                    </h5>
                                </a>
                                @php($priceDisplay = $product->has_variants ? (data_get($product->price_range, 'display') ?: ('₱' . number_format($product->price, 0))) : ('₱' . number_format($product->price, 0)))
                                <p class="card-text mb-2" style="font-size: 1.05rem; font-weight: 900; color: #111827;">{{ $priceDisplay }}</p>

                                <div class="mb-3" style="margin-top: -8px;">
                                    @php($state = $product->stock_state)
                                    <span style="font-size: 0.9rem; color: #6c757d;">
                                        @if($product->has_variants)
                                            Multiple options
                                        @elseif($state === 'unlimited')
                                            In stock
                                        @elseif($state === 'out_of_stock')
                                            Out of stock
                                        @elseif($state === 'low_stock')
                                            <span style="color:#5f6368; font-weight: 600;">Only {{ (int)($product->effective_stock ?? 0) }} left</span>
                                        @else
                                            In stock ({{ (int)($product->effective_stock ?? 0) }})
                                        @endif
                                    </span>
                                </div>
                                
                                <!-- Category and Buttons -->
                                <div class="d-flex justify-content-between align-items-center mt-auto product-actions">
                                    @if($product->category)
                                        <span class="badge" style="background: linear-gradient(135deg, #06131a 0%, #1a3a52 100%); font-size: 0.75rem; padding: 4px 8px;">{{ $product->category }}</span>
                                    @else
                                        <span></span>
                                    @endif
                                    <div class="d-flex gap-2 product-actions-buttons">
                                        <button class="btn btn-outline-warning add-to-cart-btn" 
                                                data-product-id="{{ $product->product_id }}"
                                                data-has-variants="{{ $product->has_variants ? 1 : 0 }}"
                                                style="border-radius: 10px; padding: 9px 12px; border: 2px solid #ffc107; transition: all 0.3s ease;"
                                                {{ $product->is_out_of_stock ? 'disabled' : '' }}>
                                            <i class="fas fa-shopping-cart"></i>
                                        </button>
                                        <button class="btn btn-warning buy-now-btn" 
                                                data-product-id="{{ $product->product_id }}"
                                                data-has-variants="{{ $product->has_variants ? 1 : 0 }}"
                                                style="border-radius: 10px; font-weight: 800; padding: 9px 14px; background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); border: none; transition: all 0.3s ease;"
                                                {{ $product->is_out_of_stock ? 'disabled' : '' }}>
                                            <i class="fas fa-bolt me-1"></i>{{ $product->has_variants ? 'Select Options' : 'Buy Now' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p class="text-muted">No products available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Product View Modal -->
    <div class="modal fade" id="productViewModal" tabindex="-1" aria-labelledby="productModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom">
                    <h5 class="modal-title" id="productModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <img id="pvImage" src="" class="product-img" alt="Product">
                    </div>
                    <p id="pvDesc" class="mt-3 text-muted"></p>
                    <h4 id="pvPrice" class="text-success mt-3" style="font-size: 1.5rem; font-weight: 700;"></h4>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="pvAddBtn"><i class="fas fa-shopping-cart"></i> Add to Cart</button>
                    <button type="button" class="btn btn-success" id="pvBuyBtn"><i class="fas fa-bolt"></i> Buy Now</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/cart.js') }}"></script>
        <script src="{{ asset('js/products.js') }}"></script>
        <script>
            function NewsletterSignup() {
                const email = document.getElementById('newsletterEmail').value || '';
                if (!email) return;
 else {
                    alert('Thanks — we will send updates to ' + email);
                }
                document.getElementById('newsletterEmail').value = '';
            }

            // Add hover effects to product cards
            document.addEventListener('DOMContentLoaded', function() {
                const productUrlBase = @json(url('/product'));
                const productCards = document.querySelectorAll('.product-card');
                
                productCards.forEach(card => {
                    const img = card.querySelector('.card-img-top');
                    const overlay = card.querySelector('.overlay');
                    const badge = overlay?.querySelector('.badge');
                    
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-8px)';
                        this.style.boxShadow = '0 12px 24px rgba(0, 0, 0, 0.15)';
                        if (img) img.style.transform = 'scale(1.05)';
                        if (overlay) overlay.style.background = 'rgba(0, 0, 0, 0.05)';
                        if (badge) badge.style.opacity = '1';
                    });
                    
                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                        this.style.boxShadow = '0 2px 4px rgba(0, 0, 0, 0.1)';
                        if (img) img.style.transform = 'scale(1)';
                        if (overlay) overlay.style.background = 'rgba(0, 0, 0, 0)';
                        if (badge) badge.style.opacity = '0';
                    });
                });

                // Add to Cart functionality
                document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const productId = this.dataset.productId;
                        const hasVariants = this.dataset.hasVariants === '1';

                        if (window.isAuthenticated === false) {
                            showLoginModal();
                            return;
                        }

                        if (hasVariants) {
                            window.location.href = `${productUrlBase}/${productId}`;
                            return;
                        }
                        
                        fetch('{{ route("cart.add") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                quantity: 1
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast('Success', data.message, 'success');
                                updateCartCount();
                            } else {
                                if (data.requires_login) {
                                    showLoginModal();
                                    return;
                                }
                                showToast('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Error', 'Failed to add to cart', 'error');
                        });
                    });

                    // Hover effect
                    btn.addEventListener('mouseenter', function() {
                        this.style.background = '#ffc107';
                        this.style.color = '#000';
                    });
                    btn.addEventListener('mouseleave', function() {
                        this.style.background = 'transparent';
                        this.style.color = '#ffc107';
                    });
                });

                // Buy Now functionality
                document.querySelectorAll('.buy-now-btn').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        e.stopPropagation();
                        const productId = this.dataset.productId;
                        const hasVariants = this.dataset.hasVariants === '1';

                        if (hasVariants) {
                            window.location.href = `${productUrlBase}/${productId}`;
                            return;
                        }
                        
                        fetch('{{ route("buy-now") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                quantity: 1
                            })
                        })
                        .then(async (response) => {
                            if (response.redirected) {
                                window.location.href = response.url;
                                return null;
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data && data.success) {
                                window.location.href = data.redirect || '{{ route("checkout.index") }}';
                            } else {
                                showToast('Error', data?.message || 'Unable to proceed to checkout', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Error', 'Unable to proceed to checkout', 'error');
                        });
                    });

                    // Hover effect
                    btn.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-2px)';
                        this.style.boxShadow = '0 6px 12px rgba(255, 193, 7, 0.4)';
                    });
                    btn.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                        this.style.boxShadow = 'none';
                    });
                });
            });

            // Add to Cart functionality
            document.addEventListener('DOMContentLoaded', function() {
                // Handle Add to Cart buttons
                document.querySelectorAll('.add-to-cart').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const productId = this.dataset.productId;

                        if (window.isAuthenticated === false) {
                            showLoginModal();
                            return;
                        }
                        
                        fetch('{{ route("cart.add") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                quantity: 1
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast('Success', data.message, 'success');
                                updateCartCount();
                            } else {
                                if (data.requires_login) {
                                    showLoginModal();
                                    return;
                                }
                                showToast('Error', data.message, 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Error', 'Failed to add to cart', 'error');
                        });
                    });
                });

                // Handle Buy Now buttons
                document.querySelectorAll('.buy-now').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        const productData = JSON.parse(this.dataset.product);
                        const productId = productData.product_id;
                        
                        fetch('{{ route("buy-now") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                product_id: productId,
                                quantity: 1
                            })
                        })
                        .then(async (response) => {
                            if (response.redirected) {
                                window.location.href = response.url;
                                return null;
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data && data.success) {
                                window.location.href = data.redirect || '{{ route("checkout.index") }}';
                            } else {
                                showToast('Error', data?.message || 'Unable to proceed to checkout', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Error', 'Unable to proceed to checkout', 'error');
                        });
                    });
                });
            });

            function showToast(title, message, type) {
                // Animate cart icon in navbar
                const cartIcon = document.querySelector('nav .fa-shopping-cart');
                if (cartIcon && type === 'success') {
                    cartIcon.classList.add('cart-bounce');
                    setTimeout(() => {
                        cartIcon.classList.remove('cart-bounce');
                    }, 600);
                }
            }
            
            // Add cart animation CSS
            const style = document.createElement('style');
            style.textContent = `
                @keyframes cartBounce {
                    0%, 100% { transform: scale(1); }
                    25% { transform: scale(1.4); }
                    50% { transform: scale(0.9); }
                    75% { transform: scale(1.3); }
                }
                .cart-bounce {
                    animation: cartBounce 0.6s ease;
                    color: #ffc107 !important;
                }
            `;
            document.head.appendChild(style);

            function updateCartCount() {
                fetch('{{ route("cart.get") }}')
                    .then(response => response.json())
                    .then(data => {
                        const cartBadge = document.getElementById('cartCount');
                        if (cartBadge && data.cart_count !== undefined) {
                            cartBadge.textContent = data.cart_count;
                            cartBadge.style.display = data.cart_count > 0 ? 'inline-block' : 'none';
                        }
                    });
            }
        </script>
    @endpush
@endsection
