<?php

use App\Http\Controllers\Farmer\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Farmer\Auth\RegisteredUserController;
use App\Http\Controllers\Farmer\ProfileController;
use App\Http\Controllers\Farmer\FarmController;
use App\Http\Controllers\Farmer\BatchController;
use App\Http\Controllers\Farmer\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest:farmer')->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth:farmer')->prefix('farmer')->name('farmer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware(['verified'])
        ->name('dashboard');

    // Farm Management Routes
    Route::resource('farms', FarmController::class);

    // Batch Management Routes
    Route::resource('batches', BatchController::class);

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});
