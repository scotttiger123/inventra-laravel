<?php
namespace App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    function index () {
         
        return view('auth.login');
    }
    
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $jobId = $request->query('jobId');

    // Attempt to authenticate the user
    if (Auth::attempt($request->only('email', 'password'))) {
        // Check if the authenticated user exists
        if (Auth::check()) {
            // Check if the authenticated user's email is verified
            if (Auth::user()->email_verified_at !== null) {
                    
                        return redirect()->route('welcome'); // Redirect admins to /employers
                    

            } else {
                // Email not verified
                Auth::logout(); // Logout the user
                //return redirect()->route('login')->withErrors(['Your email address is not verified.']);
                return redirect()->route('login')->withInput()->withErrors(['Your email address is not verified.']);

            }
        } else {
            // User is not authenticated (this branch should not be reached if Auth::attempt was successful)
            return redirect()->route('login')->withInput()->withErrors(['Your login details are incorrect']);

        }
    } else {
        // Authentication failed
        
        return redirect()->route('login')->withInput()->withErrors(['Your login details are incorrect']);

    }
}

    function register(){
        
        return view('auth.register');
    }

    function forgot(){
        
        return view('auth.forgot');
    }

    function forgot_pass(){
        
        return view('auth.forgot');
    }

    function logout(){
        \Session::flush();
        \Auth::logout();
        return redirect('login');
    }

    function register_user(Request $request)
    {
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:4|confirmed',
        ]);
    
        
        $user = new User();
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = Hash::make($validatedData['password']);
        $user->save();
    
         Auth::login($user);
    
        return redirect('dashboard');
    }
}
