<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApplicationController;

Route::prefix('api')->group(function () {
    Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
        return $request->user();
    });

    // Authentication routes (no auth required)
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    
    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        
        // Application routes
        Route::apiResource('applications', ApplicationController::class);
        Route::get('applications/{id}/certificate', [ApplicationController::class, 'getCertificate']);
        Route::get('applications/{id}/invoice', [ApplicationController::class, 'getInvoice']);
        Route::get('applications/{id}/age', [ApplicationController::class, 'getAge']);
    });
});