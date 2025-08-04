<?php 

use App\Http\Controllers\OtpController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckEmailVerified;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Google2FAController;
use App\Http\Middleware\Google2FAMiddleware;
use App\Http\Controllers\Auth\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckEmailVerified::class,
    Google2FAMiddleware::class,
    //  'google2fa',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/verify-otp', [OtpController::class, 'showOtpForm'])->name('otp.form');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('otp.verify');
Route::get('/resend-otp', [OtpController::class, 'resendOtp'])->name('otp.resend');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showEmailForm'])->name('forgot.password.form');
Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('forgot.password.sendOtp');

Route::get('/forgot-password/verify', [ForgotPasswordController::class, 'showOtpForm'])->name('forgot.password.verifyOtpForm');
Route::post('/forgot-password/verify', [ForgotPasswordController::class, 'verifyOtp'])->name('forgot.password.verifyOtp');

Route::get('/forgot-password/reset', [ForgotPasswordController::class, 'showResetForm'])->name('forgot.password.resetForm');
Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('forgot.password.resetPassword');

Route::get('/2fa/setup', [Google2FAController::class, 'show2FASetupForm'])->name('2fa.setup');
Route::post('/2fa/enable', [Google2FAController::class, 'enable2FA'])->name('2fa.enable');
Route::post('/2fa/disable', [Google2FAController::class, 'disable2FA'])->name('2fa.disable');

// Xác thực 2FA khi login
Route::get('/2fa/verify', [Google2FAController::class, 'show2FAVerifyForm'])->name('2fa.verify.form');
Route::post('/2fa/verify', [Google2FAController::class, 'verify2FA'])->name('2fa.verify');

Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
