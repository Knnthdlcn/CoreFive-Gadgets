@extends('layouts.app')

@section('title', 'Forgot Password (OTP)')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0" style="border-radius: 16px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12); overflow: hidden;">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #06131a 0%, #1a3a52 100%); padding: 26px 24px;">
                    <h4 class="m-0" style="color:#fff; font-weight: 800;">Reset via Email Code</h4>
                    <p class="m-0" style="color: rgba(255,255,255,0.7); font-size: 0.95rem;">We’ll email you a 6-digit OTP code.</p>
                </div>

                <div class="card-body" style="padding: 24px;">
                    @if (config('mail.default') === 'log')
                        <div class="alert alert-warning" role="alert">
                            Email sending is not configured (MAIL_MAILER=log). OTP emails will NOT be delivered.
                            Configure Gmail SMTP in your <strong>.env</strong>.
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.otp.email') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 700;">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" style="border-radius: 10px; padding: 12px 14px;" placeholder="you@example.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%); color:#fff; border:none; border-radius: 10px; padding: 12px 16px; font-weight: 800; box-shadow: 0 8px 18px rgba(21, 101, 192, 0.25);">
                            Send OTP Code
                        </button>

                        <div class="text-center mt-3" style="display:flex; gap:12px; justify-content:center;">
                            <a href="{{ route('password.request') }}" style="text-decoration:none; font-weight: 700; color: #1565c0;">Use reset link</a>
                            <span style="color:#cbd5e1;">•</span>
                            <a href="{{ route('home') }}" style="text-decoration:none; font-weight: 700; color: #1565c0;">Back to Home</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
