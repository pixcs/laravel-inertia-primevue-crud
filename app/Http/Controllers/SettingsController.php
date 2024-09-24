<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use inertia\Inertia;

class SettingsController extends Controller
{
    public function index() {
        sleep(1); 
        return Inertia::render('Settings');
    }
}
