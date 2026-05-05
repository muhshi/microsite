<?php

use App\Http\Controllers\Auth\SsoController;
use App\Http\Controllers\RedirectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [SsoController::class, 'redirect'])->name('login');
Route::get('/auth/sipetra/redirect', [SsoController::class, 'redirect'])->name('sipetra.login');
Route::get('/auth/sipetra/callback', [SsoController::class, 'callback']);

Route::get('/{code}', [RedirectController::class, 'handle'])
    ->name('redirect.handle')
    ->where('code', '[a-zA-Z0-9_-]+');
