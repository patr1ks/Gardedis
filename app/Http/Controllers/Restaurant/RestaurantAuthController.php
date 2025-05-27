<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class RestaurantAuthController extends Controller
{
    public function showLoginForm()
    {
        return Inertia::render('RestaurantOwner/Auth/Login');
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
    
            if ($user->isRestaurant) {
                session()->flash('success', 'Logged in successfully!');
                return redirect()->route('restaurantOwner.dashboard');
            }
    
            Auth::logout();
            return back()->with('error', 'You do not have permission to access the restaurant owner panel.');
        }
    
        return back()->with('error', 'Invalid credentials.');
    }     

    public function logout(Request $request)
    {        
        Auth::guard('web')->logout();
       
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // return redirect('/');
        return redirect()->route('restaurantOwner.login');
    }
}
