<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EnrollmentController;

Route::get('/', function () {
    return view('master');
});

Route::prefix(('/enrollment'))->group(function() {
    Route::get('/', [EnrollmentController::class, 'index'])->name('enrollment.index');
    Route::post('/create', [EnrollmentController::class, 'create'])->name('enrollment.create');
    Route::post('/update', [EnrollmentController::class, 'update'])->name('enrollment.update');
    Route::delete('/enrollment/{class_id}/{student_id}', [EnrollmentController::class, 'destroy'])->name('enrollment.destroy');
});
