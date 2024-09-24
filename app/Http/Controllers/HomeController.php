<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use inertia\Inertia;

class HomeController extends Controller
{
   public function index() {
      $numbersAndSymbols = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 0, '+', '-', '*', '/', '.', '=', 'Clear');

      return Inertia::render('Home', compact('numbersAndSymbols'));
   }
}
