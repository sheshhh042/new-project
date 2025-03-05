<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OtpController extends Controller
{
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        // Get the authenticated user
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->withErrors(['error' => 'Session expired. Please log in again.']);
        }

        // Check if OTP matches and is not expired
        if ($user->otp === $request->otp && Carbon::now()->lessThanOrEqualTo($user->otp_expires_at)) {
            // Clear OTP after successful verification
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->save();

            // Redirect based on role
            if ($user->role === 'admin') {
                return redirect()->route('dashboard')->with('success', 'OTP verified successfully.');
            } else {
                return redirect()->route('research.userView')->with('success', 'OTP verified successfully.');
            }
        }

        return redirect()->route('otp.verify')->withErrors(['otp' => 'Invalid or expired OTP.']);
    }
    public function showOtpForm()
{
    return view('auth.otp'); // Ensure this matches the actual blade file location
}
}