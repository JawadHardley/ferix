<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\TransporterAuthController;
use App\Http\Controllers\VendorAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('landingpage');
}); 

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
    Route::get('register', [AdminAuthController::class, 'showRegisterForm'])->name('register');
    Route::get('admins', [AdminAuthController::class, 'showAdmins'])->name('showAdmins')->middleware('role');
    Route::get('users', [AdminAuthController::class, 'showUsers'])->name('showUsers')->middleware('role');
    Route::post('register', [AdminAuthController::class, 'register']);
    Route::delete('deleteuser/{id}', [AdminAuthController::class, 'deleteUser'])->name('deleteUser')->middleware('role');
    Route::patch('users/{id}/toggle-auth', [AdminAuthController::class, 'toggleAuth'])->name('toggleAuth')->middleware('role');

    
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
    
    Route::middleware('role')->get('dashboard', function () {
        return view('transporter.dashboard');
    })->name('dashboard');
    
});

// Vendor routes
Route::prefix('vendor')->name('vendor.')->group(function () {
    Route::get('login', [VendorAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [VendorAuthController::class, 'login']);
    Route::get('register', [VendorAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [VendorAuthController::class, 'register']);

    Route::middleware('role')->get('dashboard', function () {
        return view('vendor.dashboard');
    })->name('dashboard');

});