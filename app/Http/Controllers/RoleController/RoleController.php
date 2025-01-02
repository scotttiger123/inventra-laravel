<?php 

namespace App\Http\Controllers\RoleController;

use App\Http\Controllers\Controller; 
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Role;
use App\Models\Permission;

class RoleController extends Controller
{
   
        // Show all roles
        public function index()
        {
            
            $roles = Role::all(); // Fetch all roles
            return view('roles.index', compact('roles')); // Pass roles to the view
        }
    
        // Show create role form
        public function create()
        {
            
            $roles = Role::all(); // Fetch all roles
            return view('roles.create', compact('roles')); // Pass roles to the view
        }
    
        // Store new role
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|unique:roles,name',
                'description' => 'required',
            ]);
    
            Role::create($request->all());
    
            return redirect()->route('roles.index')->with('success', 'Role created successfully');
        }
    
        // Show edit role form
        public function edit($id)
        {
            $role = Role::findOrFail($id);
            return view('roles.edit', compact('role'));
        }
    
        // Update role
        public function update(Request $request, $id)
        {
            $request->validate([
                'name' => 'required|unique:roles,name,' . $id,
                'description' => 'required',
            ]);
    
            $role = Role::findOrFail($id);
            $role->update($request->all());
    
            return redirect()->route('roles.index')->with('success', 'Role updated successfully');
        }
        
        // Delete role
        public function destroy($id)
        {
            $role = Role::findOrFail($id);
            $role->delete();
    
            return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
        }


        public function permissions(Role $role)
        {
            // Get all permissions for the role
            $permissions = $role->permissions;

            return view('roles.permissions', compact('role', 'permissions'));
        }


        
        

        public function showPermissions($roleId)
        {
            $role = Role::findOrFail($roleId);  
            $permissions = Permission::all();  
        
            // Fetch the permissions related to the role
            $rolePermissions = $role->permissions->pluck('name')->toArray();  // Get an array of permission names
        
            return view('roles.permissions', compact('role', 'permissions', 'rolePermissions'));
        }
        

        public function updatePermissions(Request $request, $roleId)
        {
            $role = Role::findOrFail($roleId);
        
            
            $request->validate([
                'permissions' => 'array|nullable',
                'permissions.*' => 'exists:permissions,name', 
            ]);
        
                    // Log the permissions being posted
            \Log::info('Permissions Posted:', $request->permissions);
            $permissionIds = Permission::whereIn('name', $request->permissions)->pluck('id')->toArray();
        
            
            $role->permissions()->sync($permissionIds);
        
            return redirect()->route('roles.permissions', $role->id)
                ->with('success', 'Permissions updated successfully!');
        }
        
    }

