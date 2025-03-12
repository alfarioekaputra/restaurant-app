<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('admin.dashboard');

    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('menu-items', MenuItemController::class);
    Route::put('menu-items/{id}/update-available', [MenuItemController::class, 'updateAvailable'])->name('menu-items.update-available');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
