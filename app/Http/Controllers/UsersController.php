<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use inertia\Inertia;

class UsersController extends Controller
{
    public function index() {
        $time = now()->toTimeString();

        return Inertia::render('Users', compact('time'));
    }
}
