<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Auth;

class OwnerLayoutController extends Controller
{
    // Save layout JSON for the logged-in user's restaurant
    public function save(Request $request)
    {
        $user = Auth::user();

        $restaurant = $user->restaurant;

        if (!$restaurant) {
            return response()->json(['error' => 'No restaurant found for user'], 404);
        }

        $restaurant->layout_json = json_encode($request->layout);
        $restaurant->save();

        return response()->json(['message' => 'Layout saved successfully']);
    }

    // Load layout JSON by restaurant ID
    public function show($id)
    {
        $restaurant = Restaurant::findOrFail($id);

        return response()->json(json_decode($restaurant->layout_json, true));
    }
}