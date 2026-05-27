<?php

use App\Http\Controllers\TrilhaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('api')->group(function () {
    Route::post('/trilhas/{id}/foto', [TrilhaController::class, 'capturePhoto']);
    Route::post('/trilhas/{id}/audio', [TrilhaController::class, 'recordAudio']);
    Route::post('/trilhas/{id}/parar', [TrilhaController::class, 'stop']);
});
