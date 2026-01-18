@extends('layouts.app')

@section('title', 'Account Disabled')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0" style="border-radius: 16px; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12); overflow: hidden;">
                <div class="card-header border-0" style="background: linear-gradient(135deg, #06131a 0%, #1a3a52 100%); padding: 26px 24px;">
                    <h4 class="m-0" style="color:#fff; font-weight: 800;">Account Disabled</h4>
                    <p class="m-0" style="color: rgba(255,255,255,0.7); font-size: 0.95rem;">Your account is temporarily unavailable.</p>
                </div>

                <div class="card-body" style="padding: 24px;">
                    <div class="alert alert-danger" role="alert" style="border-radius: 12px;">
                        {{ session('error') ?: 'Your account has been temporarily disabled (banned). Please contact Customer Service for help.' }}
                    </div>

                    <p class="text-muted mb-4" style="line-height: 1.6;">
                        If you believe this is a mistake, please reach out to us and we’ll assist you as soon as possible.
                    </p>

                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('contact.index') }}" class="btn" style="background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%); color:#fff; border:none; border-radius: 10px; padding: 12px 16px; font-weight: 800; box-shadow: 0 8px 18px rgba(21, 101, 192, 0.25);">
                            Contact Customer Service
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary" style="border-radius: 10px; padding: 12px 16px; font-weight: 800;">
                            Back to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
