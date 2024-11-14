<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\AssignmentController;

Route::get('/', function () {
    return view('master');
});

Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
    Route::get('/status/{id}', [UserController::class, 'status'])->name('status');
});

Route::prefix(('/class'))->name('classes.')->group(function () {
    Route::get('/', [ClassController::class, 'index'])->name('index');
    Route::post('/add-student', [ClassController::class, 'addStudentToClass'])->name('addStudent');
    Route::delete('/remove-student/{class_id}/{student_id}', [ClassController::class, 'removeStudentFormAClass'])->name('removeStudent');
});

Route::get('/Assignments', [AssignmentController::class, 'index'])->name('assignments.index');
Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
Route::post('/assignments/store', [AssignmentController::class, 'store'])->name('assignments.store');

Route::get('/assignments/edit/{id}', [AssignmentController::class, 'edit'])->name('assignments.edit');
Route::put('/assignments/{id}', [AssignmentController::class, 'update'])->name('assignments.update');
Route::delete('/assignments/delete/{id}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');

// Route::get('/assignments/{id}/show', [AssignmentController::class, 'showAssignment'])->name('assignments.show');
// Route::get('/assignments/{id}/hide', [AssignmentController::class, 'hideAssignment'])->name('assignments.hide');
