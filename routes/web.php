<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MicrositeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{slug}', [MicrositeController::class, 'show'])->name('microsite.show');
