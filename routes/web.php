<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\TransporterAuthController;
use App\Http\Controllers\VendorAuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

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

Route::post('/email/verification-notification', [AdminAuthController::class, 'verifyHandler'])
    ->middleware(['throttle:6,1'])
    ->name('verification.send');

Route::get('/download-certificate/{id}', [CertificateController::class, 'download'])->name('certificate.download');

Route::get('/download-draft/{id}', [CertificateController::class, 'downloaddraft'])->name('certificate.downloaddraft');
Route::get('/download-file/{id}', [CertificateController::class, 'downloadfile'])->name('file.downloadfile');

Route::get('/download-invoice/{id}', [CertificateController::class, 'downloadinvoice'])->name('invoices.downloadinvoice');

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/'); // or route to your login page
})->name('logout');

// require __DIR__.'/auth.php';

// Admin routes
Route::prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admins themselves
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);
        Route::post('update', [AdminAuthController::class, 'updateProfile'])
            ->name('updateProfile')
            ->middleware('role');
        Route::get('register', [AdminAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [AdminAuthController::class, 'register']);
        Route::get('admins', [AdminAuthController::class, 'showAdmins'])
            ->name('showAdmins')
            ->middleware('role');
        Route::get('profile', [AdminAuthController::class, 'showProfile'])
            ->name('showProfile')
            ->middleware('role')
            ->middleware('verified');
        Route::post('profile/change-password', [AdminAuthController::class, 'changePassword'])
            ->name('changePassword')
            ->middleware('role');

        // companies
        Route::get('company', [AdminAuthController::class, 'addCompanies'])
            ->name('addCompany')
            ->middleware('role');
        Route::get('companies', [AdminAuthController::class, 'showCompanies'])
            ->name('showCompanies')
            ->middleware('role');
        Route::get('company/{id}', [AdminAuthController::class, 'showCompany'])
            ->name('showCompany')
            ->middleware('role');
        Route::post('company', [AdminAuthController::class, 'storeCompany'])
            ->name('storeCompany')
            ->middleware('role');
        Route::delete('company/delete/{id}', [AdminAuthController::class, 'destroyCompany'])
            ->name('destroyCompany')
            ->middleware('role');
        Route::post('company/update/{id}', [AdminAuthController::class, 'updateCompany'])
            ->name('updateCompany')
            ->middleware('role');

        // users
        Route::get('users', [AdminAuthController::class, 'showUsers'])
            ->name('showUsers')
            ->middleware('role');
        Route::delete('deleteuser/{id}', [AdminAuthController::class, 'deleteUser'])
            ->name('deleteUser')
            ->middleware('role');
        Route::patch('users/{id}/toggle-auth', [AdminAuthController::class, 'toggleAuth'])
            ->name('toggleAuth')
            ->middleware('role');

        Route::middleware('role')
            ->get('dashboard', function () {
                return view('admin.dashboard');
            })
            ->name('dashboard');
    });

// Transporter routes
Route::prefix('transporter')
    ->name('transporter.')
    ->group(function () {
        // this transporter himself
        Route::get('login', [TransporterAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [TransporterAuthController::class, 'login']);
        Route::get('register', [TransporterAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [TransporterAuthController::class, 'register']);
        Route::get('profile', [TransporterAuthController::class, 'showProfile'])
            ->name('showProfile')
            ->middleware('role')
            ->middleware('verified');
        Route::post('profile/change-password', [TransporterAuthController::class, 'changePassword'])
            ->name('changePassword')
            ->middleware('role');
        Route::post('update', [TransporterAuthController::class, 'updateProfile'])
            ->name('updateProfile')
            ->middleware('role');

        //feri application
        Route::get('apply', [TransporterAuthController::class, 'applyferi'])
            ->name('applyferi')
            ->middleware('role');
        Route::get('applications', [TransporterAuthController::class, 'showApps'])
            ->name('showApps')
            ->middleware('role');
        Route::post('feriapp', [TransporterAuthController::class, 'feriApp'])
            ->name('feriApp')
            ->middleware('role');
        Route::get('applications/{id}/', [TransporterAuthController::class, 'showApp'])
            ->name('showApp')
            ->middleware('role');
        Route::delete('application/delete/{id}', [TransporterAuthController::class, 'destroyApp'])
            ->name('destroyApp')
            ->middleware('role');
        Route::post('application/edit/{id}', [TransporterAuthController::class, 'editApp'])
            ->name('editApp')
            ->middleware('role');
        Route::post('applications/approve/{id}/', [TransporterAuthController::class, 'process3'])
            ->name('process3')
            ->middleware('role');
        Route::post('send/{id}', [TransporterAuthController::class, 'sendchat'])
            ->name('sendchat')
            ->middleware('role');

        Route::post('edit/PO/{id}', [TransporterAuthController::class, 'editpo'])
            ->name('editpo')
            ->middleware('role');

        Route::get('chat/read/{id}/', [TransporterAuthController::class, 'readchat'])
            ->name('readchat')
            ->middleware('role');

        Route::get('chat/delete/{id}/', [TransporterAuthController::class, 'deletechat'])
            ->name('deletechat')
            ->middleware('role');

        Route::middleware('role')
            ->get('dashboard', function () {
                return view('transporter.dashboard');
            })
            ->name('dashboard');
    });

// Vendor routes
Route::prefix('vendor')
    ->name('vendor.')
    ->group(function () {
        // vendor himself
        Route::get('login', [VendorAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [VendorAuthController::class, 'login']);
        Route::post('update', [VendorAuthController::class, 'updateProfile'])
            ->name('updateProfile')
            ->middleware('role');
        Route::post('profile/change-password', [VendorAuthController::class, 'changePassword'])
            ->name('changePassword')
            ->middleware('role');
        Route::get('register', [VendorAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [VendorAuthController::class, 'register']);
        Route::get('profile', [VendorAuthController::class, 'showProfile'])
            ->name('showProfile')
            ->middleware('role')
            ->middleware('verified');

        //feri application
        Route::get('applications', [VendorAuthController::class, 'showApps'])
            ->name('showApps')
            ->middleware('role');
        Route::get('applications/{id}/', [VendorAuthController::class, 'showApp'])
            ->name('showApp')
            ->middleware('role');
        Route::post('applications/process/{id}/', [VendorAuthController::class, 'process1'])
            ->name('process1')
            ->middleware('role');
        Route::post('applications/draft/{id}/', [VendorAuthController::class, 'process2'])
            ->name('process2')
            ->middleware('role');
        Route::post('applications/certify/{id}/', [VendorAuthController::class, 'process3'])
            ->name('process3')
            ->middleware('role');
        Route::post('application/edit-doc/{id}', [VendorAuthController::class, 'updatedraft'])
            ->name('updatedraft')
            ->middleware('role');
        Route::post('send/{id}', [VendorAuthController::class, 'sendchat'])
            ->name('sendchat')
            ->middleware('role');

        Route::get('chat/read/{id}/', [VendorAuthController::class, 'readchat'])
            ->name('readchat')
            ->middleware('role');

        Route::get('chat/delete/{id}/', [VendorAuthController::class, 'deletechat'])
            ->name('deletechat')
            ->middleware('role');

        Route::middleware('role')
            ->get('dashboard', function () {
                return view('vendor.dashboard');
            })
            ->name('dashboard');
    });

Route::get('login', [TransporterAuthController::class, 'showLoginForm'])->name('login');

Route::get('/email', function () {
    return view('emailtest');
});

Route::get('/forgot-password', [AdminAuthController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [AdminAuthController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('/reset-password/{token}', [AdminAuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AdminAuthController::class, 'reset'])->name('password.update');
