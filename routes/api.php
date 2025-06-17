<?php

use App\Domain\Auth\Controllers\LoginController;
use Illuminate\Support\Facades\Route;


Route::post('/login', LoginController::class)->name('login');

Route::middleware('auth:sanctum')->group(function () {
    // Route::get('users', UserIndexController::class)->name('login');
});
