<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use App\Models\Restaurant;

class UserController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with('category', 'restaurant_images')->orderBy('id', 'desc')->limit(8)->get();
        return Inertia::render('User/Index', [
            'restaurants' => $restaurants,
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
}
