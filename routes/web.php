<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TrilhaController;

Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // Páginas Blade
    Route::get('/trilhas', [TrilhaController::class, 'index'])->name('trilhas.index');
    Route::get('/trilhas/{id}', [TrilhaController::class, 'show'])->name('trilhas.show');
    Route::get('/trilhas/{id}/ativa', [TrilhaController::class, 'ativa'])->name('trilhas.ativa');

    // Ações que resultam em redirect ou página
    Route::post('/trilhas/iniciar', [TrilhaController::class, 'start'])->name('trilhas.start');
});