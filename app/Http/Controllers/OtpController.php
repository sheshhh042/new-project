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

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);
    
        // Retrieve the user by OTP
        $user = User::where('otp', $request->otp)
                    ->where('otp_expires_at', '>=', now()) // Ensure OTP is still valid
                    ->first();
    
        if (!$user) {
            \Log::error('Invalid or expired OTP.', [
                'submitted_otp' => $request->otp,
                'current_time' => now(),
            ]);
            return redirect()->back()->withErrors(['login' => 'Invalid or expired OTP.']);
        }
    
        \Log::info('OTP verified. Logging in user.', ['user_id' => $user->id]);
    
        // Clear OTP and mark as verified
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
        ]);
    
        // Log in the user
        Auth::login($user);
    
        \Log::info('User logged in successfully. Redirecting to dashboard.', ['user_id' => $user->id]);
    
        return redirect()->route('dashboard')->with('success', 'OTP verified successfully.');
    }
    

    public function showOtpForm()
    {
        return view('auth.otp'); // Ensure this matches the actual blade file location
    }
}
