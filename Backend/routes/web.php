<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;

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

Route::prefix('course')->name('courses.')->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('index');
    Route::post('/store', [CourseController::class, 'store'])->name('store');
    Route::get('/create', [CourseController::class, 'create'])->name('create');
    Route::get('/{id}', [CourseController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [CourseController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CourseController::class, 'update'])->name('update');
    Route::delete('/{id}', [CourseController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/assignments/create', [CourseController::class, 'createAssignment'])->name('assignments.create');
    Route::post('/{id}/assignments', [CourseController::class, 'storeAssignment'])->name('assignments.store');
    Route::post('/{course}/assignments/update/{assignment}', [CourseController::class, 'updateAssignment'])->name('assignments.update');
    Route::delete('/{course}/assignments/delete/{assignment}', [CourseController::class, 'deleteAssignment'])->name('assignments.delete');
    Route::post('/add-student', [CourseController::class, 'addStudent'])->name('addStudent');
    Route::delete('/remove-student', [CourseController::class, 'removeStudent'])->name('removeStudent');
});

Route::resource('categories', CategoryController::class)->except(['destroy']);
Route::get('categories/{id}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('category.toggleStatus');
Route::get('categories/create', [CategoryController::class, 'create'])->name('category.create');
Route::post('categories', [CategoryController::class, 'store'])->name('category.store');
Route::get('categories/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
Route::put('categories/{id}', [CategoryController::class, 'update'])->name('category.update');
Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');
Route::get('categories/{id}/status', [CategoryController::class, 'status'])->name('category.status');
