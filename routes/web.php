<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrganizationAccentColorController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->get('/login', function () {
    return view('auth.login');
})->name('login');

Route::middleware('guest')->post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ]);

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    return back()->withErrors([
        'email' => trans('auth.failed'),
    ])->onlyInput('email');
});

Route::middleware('auth:web')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/account', [AccountController::class, 'show'])->name('account.show');
    Route::put('/account', [AccountController::class, 'update'])->name('account.update');
    Route::put('/account/password', [AccountController::class, 'updatePassword'])->name('account.password.update');

    Route::post('/logout', function (Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    })->name('logout');
});

Route::middleware('auth:web')->group(function (): void {
    Route::get('/customers/export', [CustomerController::class, 'export'])->name('customers.export');
    Route::resource('customers', CustomerController::class)->except(['show']);
});

Route::middleware('auth:web,organization')->match(['post', 'put'], '/settings', [OrganizationAccentColorController::class, 'update'])
    ->name('settings.update');

Route::middleware('auth:web,organization')->match(['post', 'put'], '/settings/theme', [OrganizationAccentColorController::class, 'updateTheme'])
    ->name('settings.theme.update');

Route::middleware('auth:web,organization')->post('/settings/theme/apply-all', [OrganizationAccentColorController::class, 'applyAll'])
    ->name('settings.theme.apply-all');

