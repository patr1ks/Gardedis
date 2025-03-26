<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        // $restaurants = Restaurant::with('category', 'restaurant_images')->get();
        $categories = Category::get();
        return Inertia::render('Admin/Category/Index', ['categories' => $categories]);
    }
}
