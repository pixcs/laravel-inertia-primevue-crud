<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\UsersController;
use \App\Http\Controllers\SettingsController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/users', [UsersController::class, 'index']);

Route::get('/settings', [SettingsController::class, 'index']);

// Route::post('/logout', function(Request $request) {
//     dd(request('name'));
// });