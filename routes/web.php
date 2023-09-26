<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComputerController;
use App\Http\Controllers\VisionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/computertranslator', [ComputerController::class, 'index'])->name('computertranslator.index');
Route::post('/image', [ComputerController::class, 'uploadimage'])->name('imageurl');

// Ruta para mostrar el formulario
Route::get('/formulario', [VisionController::class, 'showForm'])->name('formulario');

Route::post('/predict', [VisionController::class, 'predict'])->name('predict');
