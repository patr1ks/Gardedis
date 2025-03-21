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
        $restaurants = Restaurant::with('category', 'restaurant_images')->get();
        $categories = Category::get();
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

    public function deleteImage($id)
    {
        $restaurantImage = RestaurantImage::find($id);
            $image = RestaurantImage::where('id', $id)->delete();
            return redirect()->route('admin.restaurants.index')->with('success', 'Image deleted successfully');
    }

    public function update(Request $request, $id){

        $restaurant = Restaurant::findOrFail($id);
        
        $restaurant->title = $request->title;
        $restaurant->description = $request->description;
        $restaurant->price = $request->price;
        $restaurant->category_id = $request->category_id;
        
        if ($request->hasFile('restaurant_images')) {
            $restaurantImages = $request->file('restaurant_images');
            foreach ($restaurantImages as $image) {
                $uniqueName = time().'-'.Str::random(10).'.'.$image->getClientOriginalExtension();
                $image->move('restaurant_images', $uniqueName);
                RestaurantImage::create([
                    'restaurant_id' => $restaurant->id,
                    'image' => 'restaurant_images/'.$uniqueName,
                ]);
            }
        }
        $restaurant->update();
        return redirect()->back()->with('success', 'Restaurant updated successfully');
    }    
    

    // public function update(Request $request, $id)
    // {
    //     $restaurant = Restaurant::findOrFail($id);
    //     $restaurant->title = $request->title;
    //     $restaurant->description = $request->description;
    //     $restaurant->price = $request->price;
    //     $restaurant->category_id = $request->category_id;
    //     //chech if image was uploaded
    //     if ($request->hasFile('restaurant_images')) {
    //         $restaurantImages = $request->file('restaurant_images');
    //         foreach ($restaurantImages as $image) {
    //             //generate unique name for image
    //             $uniqueName = time().'-'.Str::random(10).'.'.$image->getClientOriginalExtension();
    //             //store image in public folder
    //             $image->move('restaurant_images', $uniqueName);
    //             //create restaurant image record
    //             RestaurantImage::create([
    //                 'restaurant_id' => $restaurant->id,
    //                 'image' => 'restaurant_images/'.$uniqueName,
    //             ]);
    //         }
    //     }
    //     $restaurant->update();
    //     return redirect()->back()->with('success', 'Restaurant updated successfully');
    // }

}
