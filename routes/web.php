<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProyectoController;
use Illuminate\Support\Facades\Route;

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

Route::get('/dashboard', [ProyectoController::class, 'index'])->middleware(['auth', 'verified'])->name('proyectos.index');
Route::get('/proyectos/create', [ProyectoController::class, 'create'])->middleware(['auth', 'verified'])->name('proyectos.create');
Route::get('/proyectos/{proyecto}/edit', [ProyectoController::class, 'edit'])->middleware(['auth', 'verified'])->name('proyectos.edit');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
