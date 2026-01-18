@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <main class="container py-5 content-with-footer">
        <!-- Page Header -->
        <div class="mb-5">
            <h2 class="mb-2" style="font-weight: 700; font-size: 2rem; color: #2c3e50;">My Profile</h2>
            <p class="text-muted mb-0">Manage your account information and settings</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px;">
                <i class="fas fa-exclamation-circle me-2"></i>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <!-- Profile Card -->
            <div class="col-lg-4 mb-4">
                <div class="card border-0 shadow-sm text-center" style="border-radius: 12px;">
                    <div class="card-body" style="padding: 32px;">
                        <form action="{{ route('profile.photo') }}" method="POST" enctype="multipart/form-data" id="photoForm">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="Profile" class="rounded-circle mb-2" style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #ffc107;">
                                @else
                                    <i class="fas fa-user-circle" style="font-size: 5rem; color: #ffc107;"></i>
                                @endif
                            </div>
                            <h4 class="mb-1" style="font-weight: 700; color: #2c3e50;">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h4>
                            <p class="text-muted mb-3">{{ Auth::user()->email }}</p>
                            <div class="d-grid gap-2">
                                <input type="file" name="profile_photo" id="profilePhotoInput" accept="image/*" style="display: none;" onchange="document.getElementById('photoForm').submit();">
                                <button type="button" class="btn" onclick="document.getElementById('profilePhotoInput').click();" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); border: none; color: #222; font-weight: 600; border-radius: 8px; padding: 10px;">
                                    <i class="fas fa-camera me-2"></i>Change Photo
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Account Information -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                    <div class="card-body" style="padding: 24px;">
                        <h5 class="card-title mb-4" style="font-weight: 700; color: #2c3e50;">
                            <i class="fas fa-info-circle me-2" style="color: #ffc107;"></i>Account Information
                        </h5>
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" style="font-weight: 600; color: #495057;">First Name</label>
                                    <input type="text" name="first_name" class="form-control" value="{{ Auth::user()->first_name }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 12px;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" style="font-weight: 600; color: #495057;">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" value="{{ Auth::user()->last_name }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 12px;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #495057;">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ Auth::user()->email }}" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 12px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #495057;">Phone Number</label>
                                <input type="tel" name="contact" class="form-control" value="{{ Auth::user()->contact }}" placeholder="09XXXXXXXXX" inputmode="numeric" maxlength="11" autocomplete="tel" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 12px;">
                                <small class="text-muted">Format: 09XXXXXXXXX</small>
                            </div>

                            <hr class="my-4" style="border-color: #e9ecef;">

                            <h6 class="mb-3" style="font-weight: 700; color: #2c3e50;">Shipping Address (Philippines)</h6>

                            <input type="hidden" name="address" id="profileAddressManual" value="{{ Auth::user()->address }}">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" style="font-weight: 600; color: #495057;">Region</label>
                                    <select id="profileRegion" name="address_region_code" class="form-select" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 12px;">
                                        <option value="">Select region</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" style="font-weight: 600; color: #495057;">Province</label>
                                    <select id="profileProvince" name="address_province_code" class="form-select" disabled style="border-radius: 8px; border: 1px solid #dee2e6; padding: 12px;">
                                        <option value="">Select province</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" style="font-weight: 600; color: #495057;">City / Municipality</label>
                                    <select id="profileCity" name="address_city_code" class="form-select" disabled style="border-radius: 8px; border: 1px solid #dee2e6; padding: 12px;">
                                        <option value="">Select city/municipality</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" style="font-weight: 600; color: #495057;">Barangay</label>
                                    <select id="profileBarangay" name="address_barangay_code" class="form-select" disabled style="border-radius: 8px; border: 1px solid #dee2e6; padding: 12px;">
                                        <option value="">Select barangay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <label class="form-label" style="font-weight: 600; color: #495057;">Street / Building / Unit</label>
                                    <input type="text" id="profileStreet" name="address_street" class="form-control" value="{{ Auth::user()->address_street }}" placeholder="House no., street, subdivision, unit" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 12px;">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" style="font-weight: 600; color: #495057;">Postal Code</label>
                                    <input type="text" id="profilePostal" name="address_postal_code" class="form-control" value="{{ Auth::user()->address_postal_code }}" placeholder="e.g. 1000" style="border-radius: 8px; border: 1px solid #dee2e6; padding: 12px;">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #495057;">Address Preview</label>
                                <textarea id="profileAddressPreview" class="form-control" rows="2" readonly style="border-radius: 8px; border: 1px solid #dee2e6; padding: 12px; background: #f8f9fa;">{{ Auth::user()->address }}</textarea>
                                <small class="text-muted">This is what will be used at checkout when you choose “saved address”.</small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%); border: none; color: #222; font-weight: 600; border-radius: 8px; padding: 12px; box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3); transition: all 0.3s ease;">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Change Password -->
                <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                    <div class="card-body" style="padding: 24px;">
                        <h5 class="card-title mb-4" style="font-weight: 700; color: #2c3e50;">
                            <i class="fas fa-lock me-2" style="color: #ffc107;"></i>Change Password
                        </h5>
                        <form action="{{ route('profile.password') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #495057;">Current Password</label>
                                <input type="password" name="current_password" class="form-control" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 12px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #495057;">New Password</label>
                                <input type="password" name="password" class="form-control" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 12px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 600; color: #495057;">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control" required style="border-radius: 8px; border: 1px solid #dee2e6; padding: 12px;">
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-warning" style="font-weight: 600; border-radius: 8px; padding: 12px; border-width: 2px; transition: all 0.3s ease;">
                                    <i class="fas fa-key me-2"></i>Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Logout (bottom of account page) -->
                <div class="card border-0 shadow-sm mt-4" style="border-radius: 12px;">
                    <div class="card-body" style="padding: 24px;">
                        <h5 class="card-title mb-3" style="font-weight: 700; color: #2c3e50;">
                            <i class="fas fa-right-from-bracket me-2" style="color: #dc3545;"></i>Logout
                        </h5>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <div class="d-grid">
                                <button type="submit" class="btn btn-outline-danger" style="font-weight: 700; border-radius: 10px; padding: 12px; border-width: 2px;">
                                    <i class="fas fa-sign-out-alt me-2"></i>Log out
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script src="{{ asset('js/ph-address.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const manualAddress = document.getElementById('profileAddressManual');
            const preview = document.getElementById('profileAddressPreview');

            const clearManual = () => {
                if (manualAddress) manualAddress.value = '';
            };

            // Force contact input to digits only and max length 11
            document.querySelector('input[name="contact"]')?.addEventListener('input', function() {
                const digitsOnly = this.value.replace(/\D/g, '').slice(0, 11);
                if (this.value !== digitsOnly) this.value = digitsOnly;
            });

            if (window.PHAddress && window.PHAddress.initSelector) {
                window.PHAddress.initSelector({
                    regionSelect: '#profileRegion',
                    provinceSelect: '#profileProvince',
                    citySelect: '#profileCity',
                    barangaySelect: '#profileBarangay',
                    streetInput: '#profileStreet',
                    postalInput: '#profilePostal',
                    previewTextarea: '#profileAddressPreview',
                    onAnyChange: clearManual,
                    initial: {
                        region: @json(Auth::user()->address_region_code),
                        province: @json(Auth::user()->address_province_code),
                        city: @json(Auth::user()->address_city_code),
                        barangay: @json(Auth::user()->address_barangay_code),
                    },
                });
            }

            // If user has no structured fields yet, keep preview as-is.
            if (preview && !preview.value) {
                preview.value = @json(Auth::user()->address);
            }
        });
    </script>
@endpush
