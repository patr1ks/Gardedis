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
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with('category', 'restaurant_images', 'user')->get();
        $categories = Category::get();
        $users = User::get();
        return Inertia::render('Admin/Restaurant/Index', ['restaurants' => $restaurants, 'categories' => $categories, 'users' => $users]);
    }
    
    public function store(Request $request)
    {
        
        $restaurant = new Restaurant();
        $restaurant->title = $request->title;
        $restaurant->description = $request->description;
        $restaurant->price = $request->price;
        $restaurant->category_id = $request->category_id;
        $restaurant->owner = $request->owner;
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
                Image::create([
                    'restaurant_id' => $restaurant->id,
                    'event_id' => null,
                    'image' => 'restaurant_images/'.$uniqueName,
                ]);
            }
        }

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant created successfully');
    }

    public function deleteImage($id)
    {
        $restaurantImage = Image::find($id);
            $image = Image::where('id', $id)->delete();
            return redirect()->route('admin.restaurants.index')->with('success', 'Image deleted successfully');
    }

    public function update(Request $request, $id){

        $restaurant = Restaurant::findOrFail($id);

        $restaurant->title = $request->title;
        $restaurant->description = $request->description;
        $restaurant->price = $request->price;
        $restaurant->category_id = $request->category_id;
        $restaurant->owner = $request->owner;
        
        if ($request->hasFile('restaurant_images')) {
            $restaurantImages = $request->file('restaurant_images');
            foreach ($restaurantImages as $image) {
                $uniqueName = time().'-'.Str::random(10).'.'.$image->getClientOriginalExtension();
                $image->move('restaurant_images', $uniqueName);
                Image::create([
                    'restaurant_id' => $restaurant->id,
                    'image' => 'restaurant_images/'.$uniqueName,
                ]);
            }
        }
        $restaurant->update();
        return redirect()->back()->with('success', 'Restaurant updated successfully');
    }
    
    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id)->delete();
        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant deleted successfully');
    }

    public function showData($id)
    {
        $restaurant = Restaurant::with('category', 'restaurant_images', 'user')->findOrFail($id);
        $categories = Category::get();
        $users = User::get();
        return response()->json(['restaurant' => $restaurant, 'categories' => $categories, 'users' => $users]);
    }
}
