<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\V1\AssignmentController;
use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\v1\ClassController;
use GuzzleHttp\Middleware;

// Public routes
Route::post('/login', [LoginController::class, 'login']);

// Admin routes
Route::middleware(['auth:sanctum', 'ability:admin'])->group(function () {
    // Add admin-specific routes here
});

// Teacher routes
Route::group(['middleware' => ['auth:sanctum', 'ability:teacher']], function () {
    // Add teacher-specific routes here
});

// Student routes
Route::group(['middleware' => ['auth:sanctum', 'ability:student']], function () {
    Route::get('/classes', [ClassController::class, 'index']);
    Route::get('/student-class', [ClassController::class, 'getStudentClasses']);
    Route::get('/assignment/{class_id}', [AssignmentController::class, 'GetAssignmentByClassId']);

    Route::get('/user', [ProfileController::class, 'showProfile'])->name('user.profile.show');
    Route::post('/user/update', [ProfileController::class, 'updateProfile'])->name('user.profile.update');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::prefix('assignment')->group(function () {
    Route::middleware(['auth:sanctum', 'ability:teacher'])->group(function () {
        Route::post('/create', [AssignmentController::class, 'CreateAssignment'])->name('create');
    });
    Route::middleware(['auth:sanctum', 'ability:student'])->group(function () {
        Route::get('detail/{id}', [AssignmentController::class, 'getAssignment'])->name('get');
        Route::post('submit', [AssignmentController::class, 'submitAssignment'])->name('submit');
    });
});
