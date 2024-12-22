<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\V1\AssignmentController;
use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\v1\ClassController;

// Public routes
Route::post('/login', [LoginController::class, 'login']);

//no role required routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/token-check', [LoginController::class, 'tokenCheck']);
});

// Teacher routes
Route::group(['middleware' => ['auth:sanctum', 'ability:teacher']], function () {
    // Add teacher-specific routes here
});

// Student routes
Route::group(['middleware' => ['auth:sanctum', 'ability:student']], function () {
    Route::prefix('classes')->group(function () {
        Route::get('/', [ClassController::class, 'index']);
        Route::get('/{id}', [ClassController::class, 'getClassById']);
        Route::get('/student-class', [ClassController::class, 'getStudentClasses']);
        Route::get('/join/{classId}', [ClassController::class, 'joinClass']);
        Route::get('/search/{classCode}', [ClassController::class, 'searchClassByCode']);
    });

    Route::get('/user', [ProfileController::class, 'showProfile'])->name('user.profile.show');
    Route::post('/user/update', [ProfileController::class, 'updateProfile'])->name('user.profile.update');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::prefix('assignment')->group(function () {
    Route::middleware(['auth:sanctum', 'ability:teacher'])->group(function () {
        Route::get('/getForTeacher', [AssignmentController::class, 'GetAssignmentForTeacher']);
        Route::post('/create', [AssignmentController::class, 'CreateAssignment']);
    });
    Route::middleware(['auth:sanctum', 'ability:student'])->group(function () {
        Route::get('{class_id}/{role}', [AssignmentController::class, 'GetAssignmentByClassId']);
        Route::get('detail/{id}/{class_id}', [AssignmentController::class, 'getAssignment']);
        Route::post('submit', [AssignmentController::class, 'submitAssignment'])->name('submit');
    });
});
