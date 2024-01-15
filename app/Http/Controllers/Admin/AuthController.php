<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Login
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
    
        // Check if the email exists
        $count = User::where('email', $request->email)->count();
        if ($count == 0) {
            return redirect()->back()->with('error', 'Email Does not exist.');
        }
    
        // Retrieve the user
        $user = User::where('email', $request->email)->first();
    
        // Check the password
        if (!Hash::check($request->password, $user->password)) {
            return redirect()->back()->with('error', 'Wrong Password.');
        }
    
        if ($this->attemptLogin($request)) {
            return redirect()->intended('admin/home');
        }
    
        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->with('error', 'Invalid password or email.');
    }
    

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        return Auth::guard('admin')->attempt(
            $request->only('email', 'password'),
            ['role_id' => [1, 2]] 
        );
    }


    // Logout
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
