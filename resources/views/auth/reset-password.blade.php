@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0" style="border-radius: 16px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12); overflow: hidden;">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #06131a 0%, #1a3a52 100%); padding: 26px 24px;">
                    <h4 class="m-0" style="color:#fff; font-weight: 800;">Reset Password</h4>
                    <p class="m-0" style="color: rgba(255,255,255,0.7); font-size: 0.95rem;">Choose a new password for your account.</p>
                </div>

                <div class="card-body" style="padding: 24px;">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 700;">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control @error('email') is-invalid @enderror" style="border-radius: 10px; padding: 12px 14px;" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 700;">New Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" style="border-radius: 10px; padding: 12px 14px;" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 700;">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" style="border-radius: 10px; padding: 12px 14px;" required>
                        </div>

                        <button type="submit" class="btn w-100" style="background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%); color:#fff; border:none; border-radius: 10px; padding: 12px 16px; font-weight: 800; box-shadow: 0 8px 18px rgba(21, 101, 192, 0.25);">
                            Update Password
                        </button>

                        <div class="text-center mt-3">
                            <a href="{{ route('home') }}" style="text-decoration:none; font-weight: 700; color: #1565c0;">Back to Home</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
