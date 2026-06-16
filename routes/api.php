<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserProfileController;


    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware(['auth:api', 'role:user'])->group(function () {

        Route::get('/me', [AuthController::class, 'me']);

        Route::post('/refresh', [AuthController::class, 'refresh']);

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/user/profile', [UserProfileController::class, 'upsert']);
        Route::get('/user/profile', [UserProfileController::class, 'show']);
        Route::get('/dashboard', function () {
            return response()->json([
                'success' => true,
                'message' => 'User Dashboard'
            ]);
        });

    });


