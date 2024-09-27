<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use inertia\Inertia;
use App\Models\Movie;
use App\Models\User;
//use Spatie\Permission\Models\Role;
//use Spatie\Permission\Traits\HasRoles;

class MoviesController extends Controller
{
    public function index() 
{
    $user = Auth::user(); 

    $movies = Movie::all();
    $rolePermissions = null;

    if ($user) {
    
        $role = $user->getRoleNames()->first(); // Get the first role
        $permissions = $user->getAllPermissions();

   
        $rolePermissions = [
            'role' => $role,
            'permissions' => $permissions
        ];

        //dd($user, $role, $permissions);
    }

    return Inertia::render('Movies/Movie', compact('movies', 'rolePermissions'));
}

}
