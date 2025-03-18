<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\Restaurant;
use App\Models\RestaurantImage;
use App\Models\Category;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::get();
        $categories = Category::get();

        $restaurants = Restaurant::get();
        return Inertia::render('Admin/Restaurant/Index', ['restaurants' => $restaurants, 'categories' => $categories]);
    }
    
    public function store(Request $request)
    {
        $restaurant = new Restaurant();
        $restaurant->title = $request->title;
        $restaurant->description = $request->description;
        $restaurant->price = $request->price;
        $restaurant->category_id = $request->category_id;
        $restaurant->save();

        //check if restaurant has images

        if ($request->hasFile('restaurant_images')) {
            $restaurantImages = $request->file('restaurant_images');
            foreach ($restaurantImages as $image) {
                //generate unique name for image
                $uniqueName = time().'-'.Str::random(10).'.'.$image->getClientOriginalExtension();
                //store image in public folder
                $image->move('restaurant_images', $uniqueName);
                //create restaurant image record
                RestaurantImage::create([
                    'restaurant_id' => $restaurant->id,
                    'image' => 'restaurant_images/'.$uniqueName
                ]);
            }
        }

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant created successfully');
    }
}
