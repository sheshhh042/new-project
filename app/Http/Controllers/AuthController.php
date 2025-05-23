<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register()
    {
        return view('auth.register');
    }
    public function registerSave(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@llcc\.edu\.ph$/|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        // Send OTP to user's email
        Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('OTP Verification');
        });

        return redirect()->route('otp.verify')->with('success', 'Registration successful. Please check your email for the OTP.');
    }

    public function createAdmin()
    {
        return view('auth.create_admin');
    }

    public function createAdminSave(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin', // Admin role
        ]);

        return redirect()->route('login')->with('success', 'Admin account created successfully. Please login.');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function loginAction(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard');
        }

        return redirect()->back()->withErrors(['email' => 'Invalid Email Address.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
    public function profile()
    {
        return view('auth.profile');
    }

    public function showOtpForm()
    {
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric',
        ]);

        // Retrieve user by OTP
        $user = User::where('otp', $request->otp)
            ->where('otp_expires_at', '>=', Carbon::now()) // Ensure OTP is still valid
            ->first();

        if (!$user) {
            return redirect()->back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        // Clear OTP after successful verification
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        // Log in the user
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'OTP verified successfully.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()->withErrors(['email' => 'No user found with this email.']);
        }

        // Generate OTP and save
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->save();

        // Send OTP via email
        Mail::raw("Your OTP is: $otp", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Your OTP Code');
        });

        return redirect()->back()->with('success', 'A new OTP has been sent to your email.');
    }
    
public function showForgotPasswordForm()
{
    return view('auth.forgot_password');
}

public function sendResetLink(Request $request)
{
    // Validate the email
    $request->validate([
        'email' => 'required|email|exists:users,email',
    ]);

    // Generate a unique token
    $token = Str::random(64);

    // Save the token in a temporary file (or cache)
    $filePath = storage_path("framework/cache/password_reset_{$request->email}.txt");
    file_put_contents($filePath, $token);

    // Generate the reset link
    $resetLink = route('password.reset.form', ['token' => $token, 'email' => $request->email]);

    // Send the reset email
    Mail::send('auth.emails.password_reset', ['resetLink' => $resetLink], function ($message) use ($request) {
        $message->to($request->email)
            ->subject('Reset Your Password');
    });

    return redirect()->back()->with('success', 'A password reset link has been sent to your email.');
}
public function showResetForm($token, Request $request)
{
    return view('auth.password_reset', [
        'token' => $token,
        'email' => $request->email,
    ]);
}

public function updatePassword(Request $request)
{
    // Validate the request
    $request->validate([
        'email' => 'required|email|exists:users,email',
        'password' => 'required|string|min:8|confirmed',
        'token' => 'required',
    ]);

    // Verify the token
    $filePath = storage_path("framework/cache/password_reset_{$request->email}.txt");
    if (!file_exists($filePath) || file_get_contents($filePath) !== $request->token) {
        return redirect()->back()->withErrors(['token' => 'This password reset token is invalid or has expired.']);
    }

    // Update the user's password
    $user = User::where('email', $request->email)->first();
    $user->password = Hash::make($request->password);
    $user->save();

    // Delete the token file
    unlink($filePath);

    return redirect()->route('login')->with('success', 'Your password has been reset successfully.');
}
};