<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Restaurant;
use Inertia\Inertia;

class MenuController extends Controller
{
    public function index()
    {
        $menu = Menu::get();
        return Inertia::render('RestaurantOwner/Menu/Index', ['menu' => $menu]);
    }

    public function store(Request $request)
    {
        $menu = new Menu();
        $menu->restaurant_id = 1; // Test value (put your test restaurant id here)
        $menu->name = $request->name;
        $menu->description = $request->description;
        $menu->price = $request->price;
        $menu->isSpecial = $request->isSpecial == 1 ? true : false;
        $menu->save();
    
        return redirect()->route('restaurantOwner.menu.index')->with('success', 'Menu item created successfully');
    }    
    
    
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);
    
        $menu->restaurant_id = $request->restaurant_id;
        $menu->name = $request->name;
        $menu->description = $request->description;
        $menu->price = $request->price;
        $menu->isSpecial = $request->isSpecial == 1 ? true : false;
    
        $menu->update();
    
        return redirect()->back()->with('success', 'Menu item updated successfully');
    }
    
    
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id)->delete();
        
        return redirect()->route('restaurantOwner.menu.index')->with('success', 'Menu item deleted successfully');
    }    

    public function showData($id)
    {
        $menu = Menu::findOrFail($id);
        return response()->json(['menu' => $menu]);
    }    
}
