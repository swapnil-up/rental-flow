<?php

use App\Admin\Properties\Controllers\PropertiesController;
use Illuminate\Support\Facades\Route;
use App\Admin\Bookings\Controllers\BookingsController;

Route::redirect('/', '/admin/properties');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('properties', PropertiesController::class);
    Route::resource('bookings', BookingsController::class);
    Route::post('bookings/{booking}/confirm', [BookingsController::class, 'confirm'])
        ->name('bookings.confirm');
    Route::post('bookings/{booking}/cancel', [BookingsController::class, 'cancel'])
        ->name('bookings.cancel');
});