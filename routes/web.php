<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BankController;


Route::redirect('/', '/admin/login');
Route::prefix('admin')->group(function () {

    Route::get('/login', [AuthController::class, 'loginForm'])->name('admin.login');

    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');

    Route::middleware(['auth', 'role:admin'])->group(function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard.index');

        Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
        
        Route::resource('banks', BankController::class, ['as' => 'admin']);

    });
});