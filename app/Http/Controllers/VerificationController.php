<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    // Show verification notice
    public function notice()
    {
        return view('auth.verify');
    }
    
    // Handle email verification
    public function verify(Request $request)
    {
        if (! $request->hasValidSignature()) {
            return redirect()
                ->route('verification.notice')
                ->with('error', 'The verification link is invalid or has expired.');
        }
        
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()
                ->route('dashboard')
                ->with('success', 'Your email is already verified.');
        }
        
        $request->user()->markEmailAsVerified();
        
        return redirect()
            ->route('dashboard')
            ->with('success', 'Your email has been verified successfully!');
    }
    
    // Resend verification email
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()
                ->route('dashboard')
                ->with('success', 'Your email is already verified.');
        }
        
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $request->user()->getKey()]
        );
        
        Mail::to($request->user()->email)->send(new VerifyEmail($verificationUrl));
        
        return back()
            ->with('success', 'A fresh verification link has been sent to your email address.');
    }
    public function sendVerificationEmail(Request $request)
{
    $verificationUrl = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        ['id' => $request->user()->id]
    );

    Mail::to($request->user()->email)->send(new VerifyEmail($verificationUrl));

}

}