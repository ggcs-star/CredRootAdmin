<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BankController;
use App\Http\Controllers\Admin\LoanTypeController;
use App\Http\Controllers\Admin\LeadStatusController;
use App\Http\Controllers\Admin\DocumentMasterController;
use App\Http\Controllers\Admin\LeadController;
use App\Http\Controllers\Admin\CustomerController;
Route::redirect('/', '/admin/login');
Route::prefix('admin')->group(function () {

    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');

    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');

    Route::middleware(['auth', 'role:admin'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');
        Route::get('/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
        Route::get('/customers/{id}', [CustomerController::class, 'show'])->name('admin.customers.show');
        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

        Route::resource('banks', BankController::class, ['as' => 'admin']);
        Route::resource('loan-types', LoanTypeController::class);
        Route::resource('lead-statuses', LeadStatusController::class);
        Route::resource('document-masters', DocumentMasterController::class);
        Route::resource('leads', LeadController::class);

        Route::post('/leads/{lead}/assign-agent', [LeadController::class, 'assignAgent'])->name('leads.assign-agent');

        Route::put('/leads/{lead}/update-status', [LeadController::class, 'updateStatus'])->name('leads.update-status');
    });
});
