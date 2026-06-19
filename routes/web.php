<?php

use App\Http\Controllers\Admin\FieldDefinitionController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ServiceController;
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

    Route::resource('services', ServiceController::class)->except('show');

    // Appointments + calendar. Extra routes declared before the resource so
    // they aren't captured by the {appointment} wildcard.
    Route::get('appointments/events', [AppointmentController::class, 'events'])->name('appointments.events');
    Route::patch('appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('appointments.complete');
    Route::resource('appointments', AppointmentController::class);

    // Phase 3+ routes (invoices, reminders) go here.
});
