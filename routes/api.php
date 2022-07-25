<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StudentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware'=>['auth:sanctum']], function () {
	Route::post('/logout', [AuthController::class, 'logout']);
	
	Route::get('/students', [StudentController::class, 'index'])->name('students');
	Route::post('/student/store', [StudentController::class, 'store'])->name('store-student');
	Route::post('/student/update/{id}', [StudentController::class, 'update'])->name('update-student');
	Route::delete('/student/delete/{id}', [StudentController::class, 'destroy'])->name('delete-student');
});
