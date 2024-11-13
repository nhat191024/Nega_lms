<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssignmentController;

Route::get('/', function () {
    return view('master');
});
Route::get('/Assignments', [AssignmentController::class, 'index'])->name('assignments.index');
Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
Route::post('/assignments/store', [AssignmentController::class, 'store'])->name('assignments.store');

Route::get('/assignments/edit/{id}', [AssignmentController::class, 'edit'])->name('assignments.edit');
Route::put('/assignments/{id}', [AssignmentController::class, 'update'])->name('assignments.update');
Route::delete('/assignments/delete/{id}', [AssignmentController::class, 'destroy'])->name('assignments.destroy');

// Route::get('/assignments/{id}/show', [AssignmentController::class, 'showAssignment'])->name('assignments.show');
// Route::get('/assignments/{id}/hide', [AssignmentController::class, 'hideAssignment'])->name('assignments.hide');


