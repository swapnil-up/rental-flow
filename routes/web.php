<?php

use App\Admin\Bookings\Controllers\BookingsController;
use App\Admin\Properties\Controllers\PropertiesController;
use App\Admin\Tenants\Controllers\TenantsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Tenant\DashboardController;
use Illuminate\Support\Facades\Route;

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store']);
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [LogoutController::class, 'destroy'])->name('logout');

    // Admin routes
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::redirect('/', '/admin/properties');
        Route::resource('properties', PropertiesController::class);
        Route::resource('bookings', BookingsController::class);
        Route::post('bookings/{booking}/confirm', [BookingsController::class, 'confirm'])
            ->name('bookings.confirm');
        Route::post('bookings/{booking}/cancel', [BookingsController::class, 'cancel'])
            ->name('bookings.cancel');
        Route::resource('tenants', TenantsController::class);
    });

    // Tenant routes
    Route::middleware('tenant')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });
});

// Fallback for logged-in users hitting /login or /register
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin() ? redirect('/admin/properties') : redirect('/dashboard');
    }
    return redirect('/login');
});