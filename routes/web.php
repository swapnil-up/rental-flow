<?php

use App\Admin\Properties\Controllers\PropertiesController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin/properties');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('properties', PropertiesController::class);
});