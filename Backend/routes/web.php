<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('master');
});

Route::prefix('users')->group(function(){
    Route::get('/', [UserController::class, 'index'])->name('users.index');
});
