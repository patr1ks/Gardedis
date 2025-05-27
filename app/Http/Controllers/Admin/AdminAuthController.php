<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return Inertia::render('Admin/Auth/Login');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
    
            if ($user->isAdmin) {
                session()->flash('success', 'Logged in successfully!');
                return redirect()->route('admin.dashboard');
            }
    
            Auth::logout(); // logout non-admin
            return back()->with('error', 'You do not have permission to access the admin panel.');
        }
    
        return back()->with('error', 'Invalid credentials.');
    }    
       

    public function logout(Request $request)
    {        
        Auth::guard('web')->logout();
       
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // return redirect('/');
        return redirect()->route('admin.login');
    }
}
