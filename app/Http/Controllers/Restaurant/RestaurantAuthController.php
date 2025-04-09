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
        // Add your login logic here
        // Check if the user is an restaurant owner and redirect accordingly
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'isRestaurant' => true])) {
            return redirect()->route('restaurantOwner.dashboard'); // Redirect to the restaurant dashboard
        }

        return redirect()->route('restaurantOwner.login')->with('error', 'Invalid credentials.');
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
