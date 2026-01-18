@extends('layouts.app')

@section('title', 'Reset Password (OTP)')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0" style="border-radius: 16px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12); overflow: hidden;">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #06131a 0%, #1a3a52 100%); padding: 26px 24px;">
                    <h4 class="m-0" style="color:#fff; font-weight: 800;">Reset Password</h4>
                    <p class="m-0" style="color: rgba(255,255,255,0.7); font-size: 0.95rem;">Enter your email + 6-digit code, then choose a new password.</p>
                </div>

                <div class="card-body" style="padding: 24px;">
                    @if (session('status'))
                        <div class="alert" role="alert" style="border-radius: 12px; border: 1px solid rgba(212,175,55,0.35); background: rgba(212,175,55,0.12); color: #1f2d3a; font-weight: 800;">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.otp.update') }}" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 700;">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $email ?? '') }}" class="form-control @error('email') is-invalid @enderror" style="border-radius: 12px; padding: 12px 14px; background: #f5f9ff; border-color: rgba(26,58,82,0.25);" autocomplete="email" inputmode="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 700;">OTP Code</label>
                            <input type="text" name="code" inputmode="numeric" maxlength="6" value="{{ old('code') }}" class="form-control @error('code') is-invalid @enderror" style="border-radius: 12px; padding: 12px 14px; letter-spacing: 6px; font-weight: 900; text-align:center; background: #f5f9ff; border-color: rgba(26,58,82,0.25);" placeholder="______">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 700;">New Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" style="border-radius: 12px; padding: 12px 14px; background: #f5f9ff; border-color: rgba(26,58,82,0.25);" autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 700;">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" style="border-radius: 12px; padding: 12px 14px; background: #f5f9ff; border-color: rgba(26,58,82,0.25);" autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%); color:#fff; border:none; border-radius: 12px; padding: 12px 16px; font-weight: 900; letter-spacing: 0.2px; box-shadow: 0 10px 22px rgba(21, 101, 192, 0.28);">
                            Update Password
                        </button>

                        <div class="text-center mt-3" style="display:flex; gap:12px; justify-content:center;">
                            <a href="{{ route('password.request', ['email' => old('email', $email ?? '')]) }}" style="text-decoration:none; font-weight: 700; color: #1565c0;">Request new OTP</a>
                            <span style="color:#cbd5e1;">•</span>
                            <a href="{{ route('home') }}" style="text-decoration:none; font-weight: 700; color: #1565c0;">Back to Home</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.querySelector('input[name="code"]')?.addEventListener('input', function() {
    const digitsOnly = this.value.replace(/\D/g, '').slice(0, 6);
    if (this.value !== digitsOnly) {
        this.value = digitsOnly;
    }
});
</script>
@endsection
