<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClassController;

Route::get('/', function () {
    return view('master');
});

Route::prefix(('/class'))->name('classes.')->group(function () {
    Route::get('/', [ClassController::class, 'index'])->name('index');
    Route::post('/add-student', [ClassController::class, 'addStudentToClass'])->name('addStudent');
    Route::delete('/remove-student/{class_id}/{student_id}', [ClassController::class, 'removeStudentFromAClass'])->name('removeStudent');
    Route::post('/add-class', [ClassController::class, 'addNewClass'])->name('addClass');
    Route::get('/hide-class/{class_id}', [ClassController::class, 'hideClass'])->name('hideClass');
});
