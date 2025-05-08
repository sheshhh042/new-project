<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class OtpController extends Controller
{
    public function generateOtp()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Session expired. Please log in again.']);
        }

        $otp = rand(100000, 999999); // Generate a 6-digit OTP
        $otpExpiryTime = Carbon::now()->addMinutes(10); // Set expiry time to 10 minutes from now

        // Store OTP and expiry time in user record
        $user->otp = $otp;
        $user->otp_expires_at = $otpExpiryTime;
        $user->save();

        // Log OTP and expiry time
        Log::info("OTP generated for user {$user->id}: {$otp}, expires at {$otpExpiryTime}");

        // Send OTP to user's email
        Mail::to($user->email)->send(new \App\Mail\OtpMail($otp));

        return redirect()->route('otp.verify')->with('success', 'OTP has been sent to your email.');
    }

    public function verify(Request $request)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Invalid or expired verification link');
        }
    
        if ($request->user()->hasVerifiedEmail()) {
            return redirect('/dashboard');
        }
    
        $request->user()->markEmailAsVerified();
    
        return redirect('/dashboard')->with('verified', true);
    }
    

    public function showOtpForm()
    {
        return view('auth.otp'); // Ensure this matches the actual blade file location
    }
}
