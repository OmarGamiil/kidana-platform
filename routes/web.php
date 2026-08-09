<?php

use App\Http\Controllers\Office\DashboardController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'marketing/Home')->name('home');
Route::inertia('access', 'marketing/Access')->name('access');

// account-type runs before account.active — a non-office account (e.g. Kidana
// team) must be rejected cleanly here, before EnsureAccountIsActive tries to
// resolve an office context that user was never meant to have.
Route::middleware(['auth', 'account-type:hajj_office', 'account.active'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/settings.php';
require __DIR__.'/activation.php';
