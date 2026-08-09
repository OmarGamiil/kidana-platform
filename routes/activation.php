<?php

use App\Http\Controllers\Activation\ActivationController;
use Illuminate\Support\Facades\Route;

// The raw token only ever appears in this one URL — every step past this
// point is guarded by activation.session (no token in the URL) instead.
Route::middleware('guest')->group(function () {
    Route::get('/activate/{activationInvite:public_id}/{token}', [ActivationController::class, 'start'])
        ->name('activation.start');

    Route::middleware('activation.session')->group(function () {
        Route::get('/activate/account', [ActivationController::class, 'showAccount'])
            ->name('activation.account.show');
        Route::post('/activate/account', [ActivationController::class, 'storeAccount'])
            ->name('activation.account.store');

        Route::get('/activate/verify', [ActivationController::class, 'showVerify'])
            ->name('activation.verify.show');
        Route::post('/activate/verify', [ActivationController::class, 'storeVerify'])
            ->middleware('throttle:otp')
            ->name('activation.verify.store');
        Route::post('/activate/verify/resend', [ActivationController::class, 'resendVerify'])
            ->middleware('throttle:otp')
            ->name('activation.verify.resend');

        Route::get('/activate/password', [ActivationController::class, 'showPassword'])
            ->name('activation.password.show');
        Route::post('/activate/password', [ActivationController::class, 'storePassword'])
            ->name('activation.password.store');
    });
});
