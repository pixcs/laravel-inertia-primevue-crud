<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MoviesController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [HomeController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/movies', [MoviesController::class, 'index'])->middleware(['auth', 'verified'])->name('movies.index');
Route::post('/movies/store', [MoviesController::class, 'store'])->middleware(['auth', 'verified'])->name('movies.store');
Route::post('/movies/update', [MoviesController::class, 'update'])->middleware(['auth', 'verified'])->name('movies.update');
Route::delete('/movies/destroy', [MoviesController::class, 'destroy'])->middleware(['auth', 'verified'])->name('movies.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
