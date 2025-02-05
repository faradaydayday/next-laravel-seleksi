<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;

// Group routes yang membutuhkan autentikasi menggunakan Sanctum
Route::middleware('auth:sanctum')->group(

    function () {
    Route::apiResource('posts', PostController::class);
    Route::post('/logout', [AuthController::class, 'logout']);

});

// Routes untuk registrasi dan login tanpa autentikasi
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
