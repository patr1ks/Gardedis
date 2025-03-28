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

    public function store(Request $request)
    {
        $category = new Category();
        $category->name = $request->name;
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
    }

    public function update(Request $request, $id){

        $category = Category::findOrFail($id);

        $category->name = $request->name;
    
        $category->update();
        return redirect()->back()->with('success', 'Category updated successfully');
    }
    
    public function destroy($id)
    {
        $restaurant = Category::findOrFail($id)->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
}
