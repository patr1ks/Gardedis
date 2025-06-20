<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\Restaurant;
use App\Models\Image;
use App\Models\Category;
use App\Models\User;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with('categories', 'restaurant_images', 'user')->get();
        $categories = Category::get();
    
        $usedUserIds = Restaurant::pluck('owner')->toArray();
    
        $users = User::where('isAdmin', 0)
            ->where('isRestaurant', 1)
            ->whereNotIn('id', $usedUserIds)
            ->get(['id', 'email']);
    
        return Inertia::render('Admin/Restaurant/Index', [
            'restaurants' => $restaurants,
            'categories' => $categories,
            'users' => $users,
        ]);
    }    

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'owner' => 'nullable|exists:users,id',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
        ]);

        $restaurant = new Restaurant();
        $restaurant->title = $validated['title'];
        $restaurant->slug = Str::slug($validated['title']);
        $restaurant->description = $validated['description'];
        $restaurant->price = $validated['price'];
        $restaurant->owner = $validated['owner'];
        $restaurant->save();

        // Attach categories
        $restaurant->categories()->sync($validated['category_ids']);

        // Upload images if available
        if ($request->hasFile('restaurant_images')) {
            foreach ($request->file('restaurant_images') as $image) {
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move('restaurant_images', $uniqueName);

                Image::create([
                    'restaurant_id' => $restaurant->id,
                    'event_id' => null,
                    'image' => 'restaurant_images/' . $uniqueName,
                ]);
            }
        }

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant created successfully');
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
    
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'owner' => 'nullable|exists:users,id',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
        ]);
    
        $restaurant->title = $validated['title'];
        $restaurant->description = $validated['description'];
        $restaurant->price = $validated['price'];
        $restaurant->owner = $validated['owner'];
        $restaurant->save();
    
        $restaurant->categories()->sync($validated['category_ids']);
    
        if ($request->hasFile('restaurant_images')) {
            foreach ($request->file('restaurant_images') as $image) {
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $image->move('restaurant_images', $uniqueName);
    
                Image::create([
                    'restaurant_id' => $restaurant->id,
                    'image' => 'restaurant_images/' . $uniqueName,
                ]);
            }
        }
    
        $restaurant = Restaurant::with('categories', 'restaurant_images', 'user')->findOrFail($restaurant->id);
    
        return response()->json([
            'success' => 'Restaurant updated successfully',
            'restaurant' => $restaurant,
        ]);
    }    

    public function deleteImage($id)
    {
        Image::where('id', $id)->delete();
        return redirect()->route('admin.restaurants.index')->with('success', 'Image deleted successfully');
    }

    public function destroy($id)
    {
        Restaurant::findOrFail($id)->delete();
        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant deleted successfully');
    }

    public function showData($id)
    {
        $restaurant = Restaurant::with('categories', 'restaurant_images', 'user')->findOrFail($id); // updated
        $categories = Category::get();
        $users = User::get();

        return response()->json([
            'restaurant' => $restaurant,
            'categories' => $categories,
            'users' => $users,
        ]);
    }

    public function togglePublished($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->published = !$restaurant->published;
        $restaurant->save();
    
        return redirect()->back()->with('success', 'Restaurant publish status updated.');
    }    
}