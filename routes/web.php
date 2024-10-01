<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MoviesController;
use App\Http\Controllers\MoviesManagementController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AuthenticatedLayoutController;

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
Route::get('/movies/get',  [MoviesController::class, 'get'])->middleware(['auth', 'verified', 'permission:read-movies'])->name('movies.get');

Route::get('/movies-management', [MoviesManagementController::class, 'index'])->middleware(['auth', 'verified', 'role:super admin|admin'])->name('movies-management.index');
Route::get('/movies-management/get', [MoviesManagementController::class, 'get'])->middleware(['auth', 'verified', 'role:super admin|admin', 'permission:read-movies'])->name('movies-management.get');
Route::post('/movies-management/store', [MoviesManagementController::class, 'store'])->middleware(['auth', 'verified', 'role:super admin|admin', 'permission:create-movies'])->name('movies-management.store');
Route::post('/movies-management/update', [MoviesManagementController::class, 'update'])->middleware(['auth', 'verified', 'role:super admin|admin', 'permission:update-movies'])->name('movies-management.update');
Route::delete('/movies-management/destroy', [MoviesManagementController::class, 'destroy'])->middleware(['auth', 'verified', 'role:super admin|admin', 'permission:update-movies'])->name('movies-management.destroy');

Route::post('/movies-management/role/store', [RoleController::class, 'store'])->middleware(['auth', 'verified', 'role:super admin|admin', 'permission:create-role'])->name('role.store');
Route::post('/movies-management/role/{id}', [RoleController::class, 'find'])->middleware(['auth', 'verified', 'role:super admin|admin'])->name('role.find');
Route::put('/movies-management/role/update', [RoleController::class, 'update'])->middleware(['auth', 'verified', 'role:super admin|admin', 'permission:update-permission'])->name('role.update');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
