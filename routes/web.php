<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\ProfileSettingsController;
use App\Http\Controllers\ResearchController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReportController;
use App\Models\Research;
use App\Http\Controllers\KeywordController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ResearchSuggestionController;
use App\Http\Controllers\SearchHistoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');
    
    Route::get('/', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');

    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});


// Password Reset Routes
Route::get('password/forgot', [AuthController::class, 'showForgotPasswordForm'])->name('password.forgot');
Route::post('password/forgot', [AuthController::class, 'sendResetLink'])->name('password.forgot.action');
Route::get('password/reset/{token}', [AuthController::class, 'showResetForm'])->name('password.reset.form');
Route::post('password/reset', [AuthController::class, 'updatePassword'])->name('password.reset.action');

// OTP Routes
Route::controller(OtpController::class)->group(function () {
    Route::get('otp', 'showOtpForm')->name('otp.verify');
    Route::post('otp', 'verifyOtp')->name('otp.verify');
    Route::post('otp/resend', 'resendOtp')->name('otp.resend');
    Route::post('/send-otp', [ProfileSettingsController::class, 'sendOtp'])->name('send.otp');
    
});

// Protected Routes (Requires Authentication)
Route::middleware('auth')->group(function () {
    Route::get('/report', [ReportController::class, 'index'])->name('report');
    
        // Report Route
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
        Route::get('/research/{id}/view', [ResearchController::class, 'viewFile'])->name('research.view');


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
     Route::put('/settings/update', [SettingController::class,'update'])->name('settings.update');
    

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
Route::post('/track-search', [KeywordController::class, 'trackSearch'])->name('track.search');

Route::get('/research/file/{id}', function($id) {
    $research = Research::findOrFail($id);
    $path = storage_path('app/public/' . $research->file_path);
    
    if (!file_exists($path)) abort(404);
    
    return response()->file($path);
});

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

// Replace your existing suggestion route with:
Route::get('/research/suggestions', [ResearchSuggestionController::class, 'index'])
    ->name('research.suggestions');