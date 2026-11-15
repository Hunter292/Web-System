<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\OTPController;
use App\Http\Controllers\Api\Auth\PasswordResetController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;



// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/2F', [AuthController::class, 'auth2F']);

// OTP routes
// Route::post('/send-otp', [OTPController::class, 'sendOTP']);
// Route::post('/verify-otp', [OTPController::class, 'verifyOTP']);


//Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

});
Route::post('/forgot-password', [AuthController::class,'forgot_password'])->name('password.email');
Route::post('/reset-password', [AuthController::class,'reset_password'])->name('password.reset');
