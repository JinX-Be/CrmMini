<?php

use App\Http\Controllers\Admin\FieldDefinitionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

// Guests
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

// Authenticated app
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('customers', CustomerController::class);

    // Owner-only: manage the niche's custom-field definitions.
    Route::resource('settings/fields', FieldDefinitionController::class)
        ->names('fields')
        ->parameters(['fields' => 'field'])
        ->middleware('can:manage-fields');

    // Phase 2+ routes (appointments, invoices, reminders) go here.
});
