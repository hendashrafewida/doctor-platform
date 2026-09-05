<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrganizationAccentColorController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect('/admin/login');
});

Route::middleware('auth:web')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware('auth:web')->group(function (): void {
    Route::get('/customers/export', [CustomerController::class, 'export'])->name('customers.export');
    Route::resource('customers', CustomerController::class)->except(['show']);
});

Route::middleware('auth:web,organization')->post('/settings', [OrganizationAccentColorController::class, 'update'])
    ->name('settings.update');

