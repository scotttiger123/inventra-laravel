<?php 

namespace App\Http\Controllers\UserController;

use App\Http\Controllers\Controller; 
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create()
    {
        $parents = User::whereNotNull('parent_id')->get(); // Fetch users with a parent_id
        $roles = Role::all(); // Fetch all roles
        return view('users.create', compact('parents', 'roles')); // Pass both variables to the view
    }
    

    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->where(function ($query) {
                    $query->where('parent_id', auth()->id())
                        ->orWhereNull('parent_id'); // for top-level users
                })
            ],
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required',
            'parent_id' => 'nullable|exists:users,id', 
            'status' => 'required|in:active,inactive',  
        ]);

        // Create the user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'parent_id' => auth()->id(), 
            'status' => $request->status,  
        ]);

        return redirect()->route('users.index');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id); // Retrieve the user by ID
        return view('users.edit', compact('user')); // Return the edit view with the user data
    }

    public function update(Request $request, User $user)
{
    // Validate the incoming data
    $request->validate([
        'name' => 'required|string|max:255',  
        'email' => [
            'required',                         
            'email',                            
            Rule::unique('users', 'email')      
                ->ignore($user->id)             
                ->where(function ($query) use ($user) {
                    $query->where('parent_id', auth()->id())
                          ->orWhereNull('parent_id'); 
                }),
        ],
        'password' => 'nullable|string|min:8|confirmed',  
        'role' => 'required|in:admin,manager,user',
        'status' => 'required|in:active,inactive',        
    ]);

    // Prepare the data for the update
    $updateData = [
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'status' => $request->status,
    ];

    // If a new password is provided, hash it and update
    if ($request->filled('password')) {
        $updateData['password'] = bcrypt($request->password);
    }

    // Perform the update
    $user->update($updateData);

    // Redirect to the users list with success message
    return redirect()->route('users.index')->with('success', 'User updated successfully!');
}

}