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
        $rolePermissions = null;

        if ($user) {
        
            $role = $user->getRoleNames()->first(); // Get the first role
            $permissions = $user->getAllPermissions();

    
            $rolePermissions = [
                'role' => $role,
                'permissions' => $permissions->toArray()
            ];

            //dd($rolePermissions);
        }

        return Inertia::render('Movies/Movie', compact('rolePermissions'));
    }

    public function get()
    {
        $movies = Movie::all();

        return response()->json($movies);
    }

}
