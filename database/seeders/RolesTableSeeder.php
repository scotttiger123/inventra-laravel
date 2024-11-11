<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create the admin role if not already exists
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'description' => 'Administrator role with full access'
        ]);

        // Create the user role if not already exists
        $userRole = Role::firstOrCreate([
            'name' => 'user',
            'description' => 'Regular user role with limited access'
        ]);

        // Get all permissions (or define specific permissions as needed)
        $permissions = Permission::all();

        // Assign all permissions to the admin role
        $adminRole->permissions()->sync($permissions->pluck('id')->toArray());

        // Assign specific permissions to the user role
        $userPermissions = Permission::whereIn('name', ['view-dashboard', 'view-profile'])->get();
        $userRole->permissions()->sync($userPermissions->pluck('id')->toArray());
    }
}
