<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OwnerLayoutController extends Controller
{
    // Render Inertia page with layout data
    public function index()
    {
        $restaurant = Auth::user()->restaurant;

        return Inertia::render('RestaurantOwner/Layout/Index', [
            'layout' => $restaurant?->layout_json,
            'restaurantId' => $restaurant?->id,
        ]);
    }

    // Save layout JSON for the logged-in user's restaurant
    public function store(Request $request)
    {
        $user = Auth::user();
        $restaurant = $user->restaurant;
    
        if (!$restaurant) {
            return redirect()->back()->withErrors(['layout' => 'No restaurant found.']);
        }
    
        $restaurant->layout_json = json_encode($request->layout);
        $restaurant->save();
    
        // If it's an XHR or fetch request (like from JavaScript), return JSON
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Layout saved successfully']);
        }
    
        // Otherwise redirect back with flash message for Inertia
        return redirect()->back()->with('success', 'Layout saved successfully!');
    }
    

    // Load layout JSON by restaurant ID
    public function show()
    {
        $restaurant = Auth::user()->restaurant;
    
        if (!$restaurant || !$restaurant->layout_json) {
            return response()->json(['tables' => [], 'walls' => []]);
        }
    
        return response()->json(json_decode($restaurant->layout_json, true));
    }    
}
