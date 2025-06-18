<?php

use App\Domain\Auth\Controllers\LoginController;
use App\Domain\User\Controllers\UserIndexController;
use Illuminate\Support\Facades\Route;


Route::post('/login', LoginController::class)->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('users', UserIndexController::class)->name('user.index');
});
