<?php

use App\Http\Controllers\Api\V1\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user/{id}', [ProfileController::class, 'showProfile']);
Route::post('/user/update/{id}', [ProfileController::class, 'updateProfile']);
