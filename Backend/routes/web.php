<?php

use App\Http\Controllers\ClassController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnrollmentController;

Route::get('/', function () {
    return view('master');
});

Route::prefix(('/enrollment'))->name('enrollment')->group(function () {
    Route::get('/', [EnrollmentController::class, 'index'])->name('index');
    Route::post('/create', [EnrollmentController::class, 'create'])->name('create');
    Route::post('/update', [EnrollmentController::class, 'update'])->name('update');
    Route::delete('/enrollment/{class_id}/{student_id}', [EnrollmentController::class, 'destroy'])->name('destroy');
});

Route::prefix(('/classes'))->name('enrollment')->group(function () {
    Route::get('/', [ClassController::class, 'index'])->name('index');
    Route::get('/create', [ClassController::class, 'create'])->name('create');
    Route::post('/store', [ClassController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [ClassController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [ClassController::class, 'update'])->name('update');
    Route::get('/status/{id}', [ClassController::class, 'status'])->name('status');
});
