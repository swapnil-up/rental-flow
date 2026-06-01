<?php

use App\Admin\Bookings\Controllers\BookingsController;
use App\Admin\Maintenance\Controllers\MaintenanceRequestsController;
use App\Admin\Payments\Controllers\PaymentsController;
use App\Admin\Properties\Controllers\PropertiesController;
use App\Admin\Tenants\Controllers\TenantsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\MaintenanceController;
use App\Http\Controllers\Tenant\PaymentController;
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
        Route::get('maintenance', [MaintenanceRequestsController::class, 'index'])
            ->name('maintenance.index');
        Route::get('maintenance/{maintenanceRequest}', [MaintenanceRequestsController::class, 'show'])
            ->name('maintenance.show');
        Route::post('maintenance/{maintenanceRequest}/transition', [MaintenanceRequestsController::class, 'transition'])
            ->name('maintenance.transition');
        Route::get('payments', [PaymentsController::class, 'index'])->name('payments.index');
        Route::get('payments/{payment}', [PaymentsController::class, 'show'])->name('payments.show');
        Route::post('payments/{payment}/paid', [PaymentsController::class, 'markPaid'])->name('payments.paid');
        Route::post('payments/{payment}/refund', [PaymentsController::class, 'markRefunded'])->name('payments.refund');
    });

    // Tenant routes
    Route::middleware('tenant')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::get('maintenance/create', [MaintenanceController::class, 'create'])->name('maintenance.create');
        Route::post('maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
        Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    });
});

// Fallback for logged-in users hitting /login or /register
Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin() ? redirect('/admin/properties') : redirect('/dashboard');
    }
    return redirect('/login');
});