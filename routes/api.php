<?php

use App\Http\Controllers\Api\DestroyAllCalculationsController;
use App\Http\Controllers\Api\DestroyCalculationController;
use App\Http\Controllers\Api\IndexCalculationController;
use App\Http\Controllers\Api\StoreCalculationController;
use Illuminate\Support\Facades\Route;

Route::middleware('session.id')->group(function () {
    Route::get('/calculations', IndexCalculationController::class);
    Route::post('/calculations', StoreCalculationController::class);
    Route::delete('/calculations/{calculation:uuid}', DestroyCalculationController::class);
    Route::delete('/calculations', DestroyAllCalculationsController::class);
});
