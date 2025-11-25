<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthenticationController;

Route::post('/register', [AuthenticationController::class, 'register']);
Route::post('/login',    [AuthenticationController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me',     [AuthenticationController::class, 'me']);
    Route::post('/logout',[AuthenticationController::class, 'logout']);

    // các route API cần auth khác đặt ở đây
    // Route::get('/posts', [PostController::class, 'index']);
});
