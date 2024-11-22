<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\v1\ClassController;

Route::get('classes', [ClassController::class, 'index']);


