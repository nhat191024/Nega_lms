<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\v1\ClassController;

Route::get('classes', [ClassController::class, 'index']);
Route::get('students-not-in-class/{classID}', [ClassController::class, 'getStudentsNotInClass']);
Route::get('teachers-not-in-class', [ClassController::class, 'getTeachersNotInClass']);

