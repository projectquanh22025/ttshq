<?php 

use App\Http\Controllers\OtpController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckEmailVerified;use App\Http\Controllers\Auth\LoginController;






Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    CheckEmailVerified::class,
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});



Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/verify-otp', [OtpController::class, 'showOtpForm'])->name('otp.form');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('otp.verify');
Route::get('/resend-otp', [OtpController::class, 'resendOtp'])->name('otp.resend');
