<?php

use App\Http\Controllers\ClassController;
use Illuminate\Support\Facades\Route;
use PhpParser\Builder\Class_;

Route::get('/', function () {
    return view('master');
});

Route::get('/', [ClassController::class, 'index'])->name('classes.index');
Route::get('/create', [ClassController::class,'create'])->name('classes.create');
Route::post('/store', [ClassController::class, 'store'])->name('classes.store');
Route::get('/edit/{id}',[ClassController::class,'edit'])->name('classes.edit');
Route::put('/update/{id}',[ClassController::class, 'update'])->name('classes.update');
Route::get('/status/{id}', [ClassController::class, 'status'])->name('classes.status');
