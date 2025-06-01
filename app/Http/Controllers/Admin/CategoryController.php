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
    
        return response()->json([
            'success' => 'Category added successfully!',
            'new_category' => $category
        ]);
    } 

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->save();
    
        return response()->json([
            'success' => 'Category updated successfully!',
            'updated_category' => $category
        ]);
    } 
    
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
    
        return response()->json(['success' => 'Category deleted successfully!']);
    }    

    public function showData($id)
{
    $category = Category::findOrFail($id);
    return response()->json(['category' => $category]);
}
}
