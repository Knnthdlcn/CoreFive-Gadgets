<?php

namespace App\Http\Controllers;

use App\Models\EmailVerificationOtp;
use App\Services\EmailVerificationOtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmailVerificationOtpController extends Controller
{
    public function notice(Request $request)
    {
        $user = $request->user();

        if ($user && method_exists($user, 'hasVerifiedEmail') && $user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        return view('auth.verify-email');
    }

    public function send(Request $request, EmailVerificationOtpService $service)
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('home');
        }

        if (method_exists($user, 'hasVerifiedEmail') && $user->hasVerifiedEmail()) {
            return redirect()->route('home');
        }

        $ok = $service->send($user, true);

        if (!$ok) {
            return back()->withErrors([
                'email' => 'Unable to send verification code right now. Please check your mail settings and try again.',
            ]);
        }

        return back()->with('status', 'verification-code-sent');
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();
        if (!$user) {
            return redirect()->route('home');
        }

        if (method_exists($user, 'hasVerifiedEmail') && $user->hasVerifiedEmail()) {
            return redirect()->route('home')->with('status', 'Email already verified.');
        }

        $otp = EmailVerificationOtp::query()
            ->where('email', $user->email)
            ->whereNull('used_at')
            ->orderByDesc('id')
            ->first();

        if (!$otp) {
            return back()->withErrors(['code' => 'No verification code request found. Please resend a new code.']);
        }

        if ($otp->expires_at->isPast()) {
            return back()->withErrors(['code' => 'This code has expired. Please resend a new code.']);
        }

        if ((int) $otp->attempts >= EmailVerificationOtpService::OTP_MAX_ATTEMPTS) {
            return back()->withErrors(['code' => 'Too many attempts. Please resend a new code.']);
        }

        $otp->attempts = (int) $otp->attempts + 1;
        $otp->save();

        if (!Hash::check($validated['code'], $otp->code_hash)) {
            return back()->withErrors(['code' => 'Invalid code. Please try again.']);
        }

        $user->email_verified_at = now();
        $user->save();

        $otp->used_at = now();
        $otp->save();

        Auth::guard('web')->login($user, true);

        return redirect()->route('home')->with('status', 'Email verified successfully.');
    }
}
