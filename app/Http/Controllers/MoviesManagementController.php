<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use inertia\Inertia;
use App\Models\Movie;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use  App\Models\User;

class MoviesManagementController extends Controller
{
    public function index() 
    {
        $movies = Movie::all();
        $permissions = Permission::all();
       
        // Retrieve roles for the authenticated user and eager load their permissions
        //$users = User::with(['roles.permissions'])->get();

        $rolesWithPermissions = Role::with('permissions')->get()->toArray();
        
        return Inertia::render('Movies/Management', compact('movies', 'permissions', 'rolesWithPermissions'));
    }

    public function find($id)
    { 
        $role = Role::with('permissions')->findOrFail($id);
    
        $permissions = $role->permissions;
    
        return response()->json([
            'roles' => $role,
        ]);
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:250',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif|max:2048',
            'category' => 'required|string',
            'runningTime' => 'required|string',
            'year' => 'required|string',
        ]);

        if ($request->hasFile('image')) {
            $destination_path = 'images';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $request->file('image')->storeAs($destination_path, $image_name);
            $validated['image'] = $image_name;

            Movie::create([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'image' => $validated['image'],
                'genre' => $validated['category'],
                'running_time' => $validated['runningTime'],
                'year' => $validated['year']
            ]);
        } 

        return redirect()->back()->with('success', 'Successfully Created');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:50',
            'description' => 'required|string|max:250',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif|max:2048',
            'category' => 'required|string',
            'runningTime' => 'required|string',
            'year' => 'required|string',
        ]);

        $movie = Movie::findOrFail($request->id);

        if ($request->hasFile('image')) {
            $destination_path = 'images';
            $image = $request->file('image');
            $image_name = $image->getClientOriginalName();
            $image->storeAs($destination_path, $image_name); 
            $validated['image'] = $image_name; 
        } else {
            $validated['image'] = $movie->image;
        }

        $movie->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'image' => $validated['image'],
            'genre' => $validated['category'],
            'running_time' => $validated['runningTime'],
            'year' => $validated['year'],
        ]);

        return redirect()->back()->with('success', 'Updated Successfully');
    }

    public function destroy(Request $request) 
    {
        $movie = Movie::findOrFail($request->id)->delete();

        return  redirect()->back()->with('success', 'Deleted Successfully');
    }
}
