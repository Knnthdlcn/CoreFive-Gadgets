@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <!-- Error/Success Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show m-3" role="alert" style="border-radius: 8px;">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show m-3" role="alert" style="border-radius: 8px;">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Page Header -->
    <section class="py-5" style="background: linear-gradient(135deg, #06131a 0%, #1a3a52 100%);">
        <div class="container text-center text-white">
            <h1 class="display-4 fw-bold mb-3">All Products</h1>
            <p class="lead mb-4">Browse our complete collection of premium gadgets and accessories</p>
        </div>
    </section>

    <div class="container py-5">
        <div class="row">
            <!-- Admin Add Product Section -->
            @if(Auth::check() && Auth::user()->role === 'admin')
            <div class="col-lg-12 mb-5">
                <div class="card border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                    <div class="card-body p-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                        <h4 class="mb-4" style="font-weight: 700; color: #2c3e50;">
                            <i class="fas fa-plus-circle me-2" style="color: #ffc107;"></i>Add New Product
                        </h4>
                        
                        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold" style="color: #2c3e50;">Product Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="Enter product name" required style="border-radius: 10px; border: 2px solid #e0e0e0; padding: 12px 16px;">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold" style="color: #2c3e50;">Price (₱)</label>
                                    <input type="number" name="price" step="0.01" class="form-control" placeholder="Enter price" required style="border-radius: 10px; border: 2px solid #e0e0e0; padding: 12px 16px;">
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-semibold" style="color: #2c3e50;">Description</label>
                                    <textarea name="description" rows="3" class="form-control" placeholder="Enter product description" required style="border-radius: 10px; border: 2px solid #e0e0e0; padding: 12px 16px; resize: none;"></textarea>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-semibold" style="color: #2c3e50;">Product Image</label>
                                    <input type="file" name="image" class="form-control" accept="image/*" style="border-radius: 10px; border: 2px solid #e0e0e0; padding: 12px 16px;">
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-lg w-100" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); border: none; color: #222; font-weight: 700; border-radius: 10px; padding: 14px; box-shadow: 0 4px 12px rgba(255, 193, 7, 0.25); transition: all 0.3s ease;">
                                        <i class="fas fa-plus me-2"></i>Add Product
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

            <!-- Products Grid -->
            <div class="col-12">
                <h3 class="mb-4" style="font-weight: 700; color: #2c3e50;">Products Catalog</h3>
                
                @if($products->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x mb-3" style="color: #ccc;"></i>
                        <p class="text-muted">No products available yet.</p>
                    </div>
                @else
                    <div class="row g-4">
                        @foreach($products as $product)
                        <div class="col-sm-6 col-lg-3">
                            <div class="card product-card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden; transition: all 0.3s ease; cursor: pointer;">
                                <div class="product-img-wrapper" style="position: relative; overflow: hidden; height: 200px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-100 h-100" style="object-fit: contain; padding: 20px;">
                                    @else
                                        <div class="d-flex align-items-center justify-content-center h-100">
                                            <i class="fas fa-image" style="font-size: 3rem; color: #ccc;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body" style="padding: 20px;">
                                    <h6 class="card-title mb-2" style="font-weight: 700; color: #2c3e50; font-size: 1rem;">{{ $product->name }}</h6>
                                    <p class="text-muted mb-3" style="font-size: 0.85rem; line-height: 1.4;">{{ Str::limit($product->description, 60) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 mb-0" style="color: #1565c0; font-weight: 700;">₱{{ number_format($product->price, 2) }}</span>
                                        <button class="btn btn-sm" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); border: none; color: #222; font-weight: 600; border-radius: 8px; padding: 8px 16px; transition: all 0.3s ease;" onclick="addToCart({{ $product->product_id }}, '{{ $product->name }}', {{ $product->price }}, '{{ $product->image }}')">
                                            <i class="fas fa-shopping-cart me-1"></i>Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function addToCart(productId, name, price, image) {
                if (!window.isAuthenticated) {
                    showLoginModal();
                    return;
                }

                if (window.Cart && window.Cart.addToCart) {
                    window.Cart.addToCart({
                        product_id: productId,
                        name: name,
                        price: price,
                        image: image
                    });
                }
            }

            // Hover effects
            document.addEventListener('DOMContentLoaded', function() {
                const cards = document.querySelectorAll('.product-card');
                cards.forEach(card => {
                    card.addEventListener('mouseenter', function() {
                        this.style.transform = 'translateY(-8px)';
                        this.style.boxShadow = '0 12px 30px rgba(0, 0, 0, 0.15)';
                    });
                    card.addEventListener('mouseleave', function() {
                        this.style.transform = 'translateY(0)';
                        this.style.boxShadow = '0 2px 8px rgba(0, 0, 0, 0.08)';
                    });
                });
            });
        </script>
    @endpush
@endsection
