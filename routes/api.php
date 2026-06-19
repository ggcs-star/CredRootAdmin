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

Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::middleware(['auth:api', 'role:user'])->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::post('/user/profile', [UserProfileController::class, 'upsert']);
    Route::get('/user/profile', [UserProfileController::class, 'show']);

    Route::get('/user/company', [CompanyController::class, 'show']);
    Route::post('/user/company', [CompanyController::class, 'upsert']);

    Route::get('/user/company/banks', [CompanyBankAccountController::class, 'show']);
    Route::post('/user/company/banks', [CompanyBankAccountController::class, 'store']);
    Route::delete('/user/company/banks/{id}', [CompanyBankAccountController::class, 'destroy']);

    Route::get('/master/entity-types', [MasterDataController::class, 'getEntityTypes']);
    Route::get('/master/loan-types', [MasterDataController::class, 'getLoanTypes']);
    Route::get('/master/documents', [MasterDataController::class, 'getRequiredDocuments']);
    Route::get('/master/banks', [MasterDataController::class, 'getActiveBanks']);

    Route::get('/lead/show', [LeadController::class, 'show']);
    Route::post('/user/loan/apply', [LeadController::class, 'applyForLoan']);
    Route::post('/documents/upload', [DocumentController::class, 'upload']);
    Route::post('/documents/finalize', [DocumentController::class, 'finalizeUploads']);
});


