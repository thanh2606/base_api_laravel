<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/verify/password', [AuthController::class, 'verifyPassword']);
    Route::put('/update/password', [AuthController::class, 'updatePassword']);
    Route::put('/update/profile', [AuthController::class, 'updateProfile']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::delete('/logout', [AuthController::class, 'logout']);
});
