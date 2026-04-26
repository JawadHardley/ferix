<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ApplicationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// Application routes
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('applications', ApplicationController::class);
    Route::get('applications/{id}/certificate', [ApplicationController::class, 'getCertificate']);
    Route::get('applications/{id}/invoice', [ApplicationController::class, 'getInvoice']);
    Route::get('applications/{id}/age', [ApplicationController::class, 'getAge']);
});