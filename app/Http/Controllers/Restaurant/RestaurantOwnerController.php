<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RestaurantOwnerController extends Controller
{
    public function index()
    {
        
        return Inertia::render('RestaurantOwner/Dashboard', ['restaurant-owner'=> 'restaurant-owner']);

    }
}
