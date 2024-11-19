<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LoginController;

Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['middleware' => ['auth:sanctum', 'ability:admin']], function () {
    // Admin routes
    // ...existing code...
});

Route::group(['middleware' => ['auth:sanctum', 'ability:teacher']], function () {
    // Teacher routes
    // ...existing code...
});

Route::group(['middleware' => ['auth:sanctum', 'ability:student']], function () {
    // Student routes
    // ...existing code...
});
