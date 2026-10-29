<?php

declare(strict_types=1);

use AvonezAuthClient\Http\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

// Route::get('avonez-auth-client', fn () => 'AvonezAuthClient placeholder route.')->name('avonez-auth-client.placeholder');
Route::middleware('web')
    ->group(function () {
        Route::get('/login', [AuthenticationController::class, 'login'])
            ->name('login');
        Route::permanentRedirect('/register', '/login');
        Route::get('/auth', [AuthenticationController::class, 'redirect']);
    });
