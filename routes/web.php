<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfileSettingsController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');
    
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');

    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});

// OTP Routes
Route::controller(OtpController::class)->group(function () {
    Route::get('otp', 'showOtpForm')->name('otp.verify');
    Route::post('otp', 'verifyOtp')->name('otp.verify');
    Route::post('otp/resend', 'resendOtp')->name('otp.resend');
});

// Protected Routes (Requires Authentication)
Route::middleware('auth')->group(function () {
    Route::get('dashboard', [ResearchController::class, 'index'])->name('dashboard');

    // Research Routes
    Route::controller(ResearchController::class)->prefix('research')->group(function () {
        Route::get('', 'index')->name('research.index'); // Main index route
        Route::get('/create', 'create')->name('research.create'); // Create new research
        Route::post('', 'store')->name('research.store'); // Store new research
        Route::get('{research}/view', 'viewFile')->name('research.view'); // View specific research file
        Route::get('{research}/edit', 'edit')->name('research.edit'); // Edit specific research
        Route::put('{research}', 'update')->name('research.update'); // Update specific research
        Route::delete('{research}', 'destroy')->name('research.destroy'); // Delete specific research
        Route::get('user-view', 'userView')->name('research.userView'); // View for users
        Route::get('department/{department}', 'department')->name('research.department'); // View by department
        Route::get('search', 'search')->name('research.search'); // Search functionality
    });
    
    // Admin-Only Research Routes
    Route::middleware('can:isAdmin')->group(function () {
        Route::controller(ResearchController::class)->prefix('research')->group(function () {
            Route::get('create', 'create')->name('research.create');
            Route::post('store', 'store')->name('research.store');
            Route::get('{research}/edit', 'edit')->name('research.edit');
            Route::put('{research}', 'update')->name('research.update');
            Route::delete('{research}', 'destroy')->name('research.destroy');
        });
    });

    // Profile & Settings Routes (Merged)

     Route::get('/profile-settings', [ProfileSettingsController::class, 'edit'])->name('profile.settings');
     Route::put('/profile-settings/update', [ProfileSettingsController::class, 'update'])->name('settings.update');
 
    // ✅ Add this missing route for profile updates
    Route::put('/profile/update', [ProfileSettingsController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile', [ProfileSettingsController::class, 'profile'])->name('profile');
    

    // Report Route
    Route::get('/report', [ReportController::class, 'index'])->name('report');
});

    // Search
    Route::get('/research/search', [ResearchController::class, 'search'])->name('research.search');

// Test Email Route
Route::get('/test-email', function () {
    Mail::raw('This is a test email', function ($message) {
        $message->to('joshuamangubat62@gmail.com')->subject('Test Email');
    });

    return 'Email sent';
});
