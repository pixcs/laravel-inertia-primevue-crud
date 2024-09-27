<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|string|max:20',
            'selectedPermissions' => 'required|array'
        ]);

        Role::create(['name' => $validated['role']]);

        //Create or get the role
        $role = Role::findByName($validated['role']);
        $role->givePermissionTo($validated['selectedPermissions']);

       return redirect()->back()->with('success', 'Successfully Created');
    }
}
