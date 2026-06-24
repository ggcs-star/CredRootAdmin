<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CompanyBankAccountController;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\DocumentController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/resend-otp', [AuthController::class, 'resendOtp']);
Route::middleware(['auth:api', 'role:user'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/active-sessions', [AuthController::class, 'activeSessions']);

    Route::post('/user/profile', [UserProfileController::class, 'upsert']);
    Route::get('/user/profile', [UserProfileController::class, 'show']);

    Route::apiResource('company', CompanyController::class);

    Route::apiResource('bank-accounts', CompanyBankAccountController::class);

    Route::get('/master/entity-types', [MasterDataController::class, 'getEntityTypes']);
    Route::get('/master/loan-types', [MasterDataController::class, 'getLoanTypes']);
    Route::get('/master/documents', [MasterDataController::class, 'getDashboardDocumentStatus']);
    Route::get('/master/banks', [MasterDataController::class, 'getActiveBanks']);

    Route::apiResource('leads', LeadController::class);

    Route::post('/documents/upload', [DocumentController::class, 'upload']);
    Route::post('/documents/finalize', [DocumentController::class, 'finalizeUploads']);
});


