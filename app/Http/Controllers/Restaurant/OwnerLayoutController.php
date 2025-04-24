<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class OwnerLayoutController extends Controller
{

    public function index()
    {
        $restaurant = Auth::user()->restaurant;
    
        return Inertia::render('RestaurantOwner/Layout/Index', [
            'layout' => $restaurant?->layout_json ? json_decode($restaurant->layout_json) : null,
            'restaurantId' => $restaurant?->id,
        ]);
    }
    
    // Save layout JSON for the logged-in user's restaurant
    public function store(Request $request)
    {
        $user = Auth::user();

        $restaurant = $user->restaurant;

        if (!$restaurant) {
            return response()->json(['error' => 'No restaurant found for user'], 404);
        }

        $restaurant->layout_json = json_encode($request->layout);
        $restaurant->store();

        return response()->json(['message' => 'Layout saved successfully']);
    }

    // Load layout JSON by restaurant ID
    public function show($id)
    {
        $restaurant = Restaurant::findOrFail($id);

        return response()->json(json_decode($restaurant->layout_json, true));
    }
}