<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'super admin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);

        Permission::create(['name' => 'read-movies']);
        Permission::create(['name' => 'create-movies']);
        Permission::create(['name' => 'update-movies']);
        Permission::create(['name' => 'delete-movies']);

        //For user Permission
        Permission::create(['name' => 'save-movies']);
        
        // Assign permissions to roles
        $role = Role::findByName('super admin');
        $role->givePermissionTo(['read-movies', 'create-movies', 'update-movies', 'delete-movies']);
        
        $roleTwo = Role::findByName('admin');
        $roleTwo->givePermissionTo(['read-movies', 'create-movies']);


        $roleThree = Role::findByName('user');
        $roleThree->givePermissionTo(['read-movies', 'save-movies']);
    }
}
