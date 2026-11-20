<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// SEMENTARA: akan diganti ProductCatalogController di M4
Route::get('/', fn () => view('shop.index'))->name('shop.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// SEMENTARA: akan diganti DashboardController di M7
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', fn () => view('admin.dashboard'))->name('dashboard');
});