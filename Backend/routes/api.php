<?php

use App\Http\Controllers\Api\V1\AssignmentController;
use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LoginController;

// Public routes
Route::post('/login', [LoginController::class, 'login']);

// Admin routes
Route::group(['middleware' => ['auth:sanctum', 'ability:admin']], function () {
    // Add admin-specific routes here
});

// Teacher routes
Route::group(['middleware' => ['auth:sanctum', 'ability:teacher']], function () {
    Route::post('/assignment/create', [AssignmentController::class, 'CreateAssignment'])->name('user.assignment.create');
});

// Student routes
Route::group(['middleware' => ['auth:sanctum', 'ability:student']], function () {
    // Add student-specific routes here
});

// Authenticated routes (no specific ability required)
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/user', [ProfileController::class, 'showProfile'])->name('user.profile.show');
    Route::post('/user/update', [ProfileController::class, 'updateProfile'])->name('user.profile.update');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
