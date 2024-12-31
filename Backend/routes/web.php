<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuizBankController;

Route::get('/', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');

Route::prefix('/dashboard')->name('dashboard.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('index');
});

Route::prefix('/users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
    Route::get('/status/{id}', [UserController::class, 'status'])->name('status');
});

Route::prefix('class')->name('classes.')->group(function () {
    Route::get('/', [ClassController::class, 'index'])->name('index');
    Route::post('/add-student', [ClassController::class, 'addStudentToClass'])->name('addStudent');
    Route::delete('/remove-student/{class_id}/{student_id}', [ClassController::class, 'removeStudentFromAClass'])->name('removeStudent');
    Route::post('/add-class', [ClassController::class, 'addNewClass'])->name('addClass');
    Route::get('/hide-class/{class_id}', [ClassController::class, 'hideClass'])->name('hideClass');
    Route::get('/edit-class/{class_id}', [ClassController::class, 'editClass'])->name('editClass');
    Route::put('/update-class/{class_id}', [ClassController::class, 'updateClass'])->name('updateClass');
});

Route::prefix('/assignment')->name('assignments.')->group(function () {
    Route::get('/', [AssignmentController::class, 'index'])->name('index');
    Route::get('/get/{id}', [AssignmentController::class, 'getAssignments'])->name('get');
    Route::get('/create', [AssignmentController::class, 'create'])->name('create');
    Route::post('/store', [AssignmentController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [AssignmentController::class, 'edit'])->name('edit');
    Route::put('/{id}', [AssignmentController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [AssignmentController::class, 'destroy'])->name('destroy');
    Route::get('/assignments/visibility/{id}', [AssignmentController::class, 'toggleVisibility'])
    ->name('assignments.visibility');
});

Route::prefix('/quiz-bank')->name('quiz-bank.')->group(function() {
    Route::get('/', [QuizBankController::class, 'index'])->name('index');
    Route::get('/create', [QuizBankController::class, 'createQuizBank'])->name('create');
    Route::post('/add', [QuizBankController::class, 'addQuestion'])->name('add');
    Route::get('/hidden', [QuizBankController::class, 'hidden'])->name('hidden');
});
