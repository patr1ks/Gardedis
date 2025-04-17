<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Restaurant;
use App\Models\Image;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class OwnerRestaurantController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $restaurant = Restaurant::with('category', 'restaurant_images')->where('owner', $user->id)->first();
    
        $categories = Category::get();
    
        return Inertia::render('RestaurantOwner/Restaurant/Index', ['restaurant' => $restaurant, 'categories' => $categories, 'user' => $user]);
    }
    


    public function deleteImage($id)
    {
        $restaurantImage = Image::find($id);
            $image = Image::where('id', $id)->delete();
            return redirect()->route('restaurantOwner.restaurant.index')->with('success', 'Image deleted successfully');
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
                Image::create([
                    'restaurant_id' => $restaurant->id,
                    'image' => 'restaurant_images/'.$uniqueName,
                ]);
            }
        }
        $restaurant->update();
        return redirect()->back()->with('success', 'Restaurant updated successfully');
    }
}
