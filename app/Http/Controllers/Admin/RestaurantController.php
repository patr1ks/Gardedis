<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use App\Models\Restaurant;
use App\Models\RestaurantImage;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

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
                    'image' => 'restaurant_images/'.$uniqueName,
                ]);
            }
        }

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant created successfully');
    }


// public function store(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'title' => 'required|string|max:255',
//         'description' => 'required|string',
//         'price' => 'required|numeric',
//         'category_id' => 'required|exists:categories,id',
//         'restaurant_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate each image
//     ]);

//     if ($validator->fails()) {
//         return back()->withErrors($validator)->withInput();
//     }

//     // Create restaurant
//     $restaurant = Restaurant::create([
//         'title' => $request->title,
//         'description' => $request->description,
//         'price' => $request->price,
//         'category_id' => $request->category_id,
//     ]);

//     // Handle images
//     if ($request->hasFile('restaurant_images')) {
//         foreach ($request->file('restaurant_images') as $image) {
//             $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
//             $image->move(public_path('restaurant_images'), $uniqueName); // Save to public folder

//             RestaurantImage::create([
//                 'restaurant_id' => $restaurant->id,
//                 'image' => 'restaurant_images/' . $uniqueName,
//             ]);
//         }
//     }

//     return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant created successfully');
// }

}
