<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\V1\AssignmentController;
use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LoginController;
use App\Http\Controllers\Api\V1\ClassController;
use App\Http\Controllers\Api\V1\CourseController;

// Public routes
Route::post('/login', [LoginController::class, 'login']);

//no role required routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/token-check', [LoginController::class, 'tokenCheck']);
    Route::get('/logout', [LoginController::class, 'logout']);
});

//both teacher and student routes
Route::middleware(['auth:sanctum', 'ability:teacher,student'])->group(function () {
    Route::get('classes/', [ClassController::class, 'index']);
    Route::get('classes/info/{id}', [ClassController::class, 'getClassById']);

    Route::get('assignment/getByClass/{class_id}/{role}', [AssignmentController::class, 'GetAssignmentByClassId']);
    Route::get('assignment/getById/{id}/{isTeacher}', [AssignmentController::class, 'getAssignmentById']);
});

// Teacher routes
Route::group(['middleware' => ['auth:sanctum', 'ability:teacher']], function () {
    Route::prefix('classes')->group(function () {
        Route::get('/point/{classId}', [ClassController::class, 'getClassAssignmentPoint']);
    });

    Route::prefix('assignment')->group(function () {
        Route::post('/create', [AssignmentController::class, 'CreateAssignment']);
        Route::post('/update', [AssignmentController::class, 'UpdateAssignment']);
        Route::post('/update-assignment-quiz', [AssignmentController::class, 'UpdateAssignmentQuizzes']);
    });

    Route::prefix('quizPackage')->group(function () {
        Route::get('/teacher-quiz-package', [QuizPackageController::class, 'getQuizPackageForTeacher']);
        Route::post('/create', [QuizPackageController::class, 'create']);
    });
});

// Student routes
Route::group(['middleware' => ['auth:sanctum', 'ability:student']], function () {
    Route::prefix('classes')->group(function () {
        Route::get('/student-class', [ClassController::class, 'getStudentClasses']);
        Route::get('/join/{classId}', [ClassController::class, 'joinClass']);
        Route::get('/search/{classCode}', [ClassController::class, 'searchClassByCode']);
        Route::get('/getStudentAssignmentPoint/{class_id}', [ClassController::class, 'getStudentAssignmentPoint']);
    });

    Route::prefix('courses')->group(function () {
        Route::get('/get', [CourseController::class, 'index']);
        Route::get('/getById/{id}', [CourseController::class, 'getCourseById']);
    });

    Route::prefix('assignment')->group(function () {
        Route::post('submit', [AssignmentController::class, 'submitAssignment']);
    });

    Route::get('/user', [ProfileController::class, 'showProfile'])->name('user.profile.show');
    Route::post('/user/update', [ProfileController::class, 'updateProfile'])->name('user.profile.update');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});
