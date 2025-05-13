<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\TransporterAuthController;
use App\Http\Controllers\VendorAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('landingpage');
}); 

    Route::get('/email/verify/success', function () {
        return view('auth.verify-success');
    })->name('verification.success');

    // Show the "verify your email" page
    Route::get('/email/verify', [AdminAuthController::class, 'verifyNotice'])->name('verification.notice');

    // Handle the actual verification from email link
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

    
    Route::post('/email/verification-notification', [AdminAuthController::class, 'verifyHandler'])->middleware(['throttle:6,1'])->name('verification.send');



Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/'); // or route to your login page
})->name('logout');

// require __DIR__.'/auth.php';
  

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('update', [AdminAuthController::class, 'updateProfile'])->name('updateProfile')->middleware('role');
    Route::get('register', [AdminAuthController::class, 'showRegisterForm'])->name('register');
    Route::get('admins', [AdminAuthController::class, 'showAdmins'])->name('showAdmins')->middleware('role');
    Route::get('users', [AdminAuthController::class, 'showUsers'])->name('showUsers')->middleware('role');
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::delete('deleteuser/{id}', [AdminAuthController::class, 'deleteUser'])->name('deleteUser')->middleware('role');
    Route::patch('users/{id}/toggle-auth', [AdminAuthController::class, 'toggleAuth'])->name('toggleAuth')->middleware('role');
    Route::get('profile', [AdminAuthController::class, 'showProfile'])->name('showProfile')->middleware('role')->middleware('verified');
    Route::post('profile/change-password', [AdminAuthController::class, 'changePassword'])->name('changePassword')->middleware('role');

    
    Route::middleware('role')->get('dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
});

// Transporter routes
Route::prefix('transporter')->name('transporter.')->group(function () {
    Route::get('login', [TransporterAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [TransporterAuthController::class, 'login']);
    Route::get('register', [TransporterAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [TransporterAuthController::class, 'register']);
    Route::get('profile', [TransporterAuthController::class, 'showProfile'])->name('showProfile')->middleware('role')->middleware('verified');
    Route::post('profile/change-password', [TransporterAuthController::class, 'changePassword'])->name('changePassword')->middleware('role');
    Route::post('update', [TransporterAuthController::class, 'updateProfile'])->name('updateProfile')->middleware('role');
    
    Route::middleware('role')->get('dashboard', function () {
        return view('transporter.dashboard');
    })->name('dashboard');
    
});

// Vendor routes
Route::prefix('vendor')->name('vendor.')->group(function () {
    Route::get('login', [VendorAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [VendorAuthController::class, 'login']);
    Route::post('update', [VendorAuthController::class, 'updateProfile'])->name('updateProfile')->middleware('role');
    Route::post('profile/change-password', [VendorAuthController::class, 'changePassword'])->name('changePassword')->middleware('role');
    Route::get('register', [VendorAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [VendorAuthController::class, 'register']);
    Route::get('profile', [VendorAuthController::class, 'showProfile'])->name('showProfile')->middleware('role')->middleware('verified');

    Route::middleware('role')->get('dashboard', function () {
        return view('vendor.dashboard');
    })->name('dashboard');

});

Route::get('login', [TransporterAuthController::class, 'showLoginForm'])->name('login');

Route::get('/email', function () {
    return view('emailtest');
}); 