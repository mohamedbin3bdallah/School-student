<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/schools', [SchoolController::class, 'index'])->name('schools');
Route::get('/school/add', [SchoolController::class, 'create'])->name('add-school');
Route::post('/school/store', [SchoolController::class, 'store'])->name('store-school');
Route::get('/school/edit/{id}', [SchoolController::class, 'edit'])->name('edit-school');
Route::post('/school/update/{id}', [SchoolController::class, 'update'])->name('update-school');
Route::delete('/school/delete/{id}', [SchoolController::class, 'destroy'])->name('delete-school');
Route::get('/students', [StudentController::class, 'index'])->name('students');
Route::get('/student/add', [StudentController::class, 'create'])->name('add-student');
Route::post('/student/store', [StudentController::class, 'store'])->name('store-student');
Route::get('/student/edit/{id}', [StudentController::class, 'edit'])->name('edit-student');
Route::post('/student/update/{id}', [StudentController::class, 'update'])->name('update-student');
Route::delete('/student/delete/{id}', [StudentController::class, 'destroy'])->name('delete-student');
