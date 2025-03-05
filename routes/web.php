<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ResearchContoller;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
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
        Route::get('', 'index')->name('research');
        Route::get('view/{research}', 'view')->name('research.view');
        Route::get('/research/{id}/view', [ResearchController::class, 'viewFile'])->name('research.view');
        Route::get('user-view', 'userView')->name('research.userView');
        Route::get('department/{department}', 'department')->name('research.department');
        Route::get('search', 'search')->name('research.search');
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

    // Other Authenticated Routes
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::get('/report', [ReportController::class, 'index'])->name('report');
});

// Test Email Route
Route::get('/test-email', function () {
    Mail::raw('This is a test email', function ($message) {
        $message->to('joshuamangubat62@gmail.com')->subject('Test Email');
    });

    return 'Email sent';
});