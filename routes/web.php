<?php
use App\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);
Route::get('/verify-otp', [OtpController::class, 'showOtpForm'])->name('otp.form');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('otp.verify');

