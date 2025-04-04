<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfileSettingsController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\SearchHistoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

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
        Route::get('', 'index')->name('research.index');
        Route::get('/create', 'create')->name('research.create');
        Route::post('', 'store')->name('research.store');
        Route::get('{research}/view', 'viewFile')->name('research.view');
        Route::get('{research}/edit', 'edit')->name('research.edit');
        Route::put('{research}', 'update')->name('research.update');
        Route::delete('{research}', 'destroy')->name('research.destroy');
        Route::get('user-view', 'userView')->name('research.userView');
        Route::get('department/{department}', 'department')->name('research.department');
        Route::get('search', 'search')->name('research.search');

        // Recently Deleted Research Routes
        Route::get('/recently-deleted', [ResearchController::class, 'recentlyDeleted'])->name('research.recentlyDeleted');
        Route::post('/restore/{id}', [ResearchController::class, 'restore'])->name('research.restore');
        Route::delete('/permanent-delete/{id}', [ResearchController::class, 'permanentDelete'])->name('research.permanentDelete');
 
        Route::delete('/search-history/{id}', [SearchHistoryController::class, 'destroy'])->name('search-history.destroy');
        
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

    // Profile & Settings Routes
    Route::get('/profile-settings', [ProfileSettingsController::class, 'edit'])->name('profile.settings');
    Route::put('/profile-settings/update', [ProfileSettingsController::class, 'update'])->name('settings.update');
    Route::put('/profile/update', [ProfileSettingsController::class, 'updateProfile'])->name('profile.update');
    Route::get('/profile', [ProfileSettingsController::class, 'profile'])->name('profile');
    
    // Report Route
    Route::get('/report', [ReportController::class, 'index'])->name('report');

});

// Feedback Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/feedback', [FeedbackController::class, 'userFeedback'])->name('feedback.user'); // User feedback page
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store'); // Handle feedback submission
    Route::get('/admin/feedback', [FeedbackController::class, 'index'])->name('feedback.admin'); // Admin feedback page

        Route::middleware(['auth', 'can:isAdmin'])->group(function () {
        Route::get('/admin/feedback', [FeedbackController::class, 'index'])->name('feedback.admin'); // Admin feedback page
        Route::post('/admin/feedback/{feedback}/mark-as-read', [FeedbackController::class, 'markAsRead'])->name('admin.feedback.markAsRead'); // Mark feedback as read
        Route::delete('/admin/feedback/{feedback}', [FeedbackController::class, 'destroy'])->name('admin.feedback.destroy'); // Delete feedback
    });
});

// Search Route
Route::get('/research/search', [ResearchController::class, 'search'])->name('research.search');



Route::get('/keywords/create', [KeywordController::class, 'create'])->name('keywords.create');
Route::post('/keywords', [KeywordController::class, 'store'])->name('keywords.store');


Route::get('/keywords', [KeywordController::class, 'index'])->name('keywords.index');

// Test Email Route (Optional)
Route::get('/test-email', function () {
    Mail::raw('This is a test email', function ($message) {
        $message->to('joshuamangubat62@gmail.com')->subject('Test Email');
    });

    return 'Email sent';
});
