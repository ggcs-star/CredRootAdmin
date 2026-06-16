<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::prefix('auth')->group(function () {

    // Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);   
 Route::post('/login', [AuthController::class, 'login']);

    // Protected Routes
    Route::middleware(['auth:api', 'role:user'])->group(function () {

        Route::get('/me', [AuthController::class, 'me']);

        Route::post('/refresh', [AuthController::class, 'refresh']);

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::get('/dashboard', function () {
            return response()->json([
                'success' => true,
                'message' => 'User Dashboard'
            ]);
        });

    });

});
