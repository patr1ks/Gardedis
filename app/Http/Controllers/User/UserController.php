<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use App\Models\Restaurant;
use App\Models\Event;
use App\Models\RestaurantForm;

class UserController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with('category', 'restaurant_images')->orderBy('id', 'desc')->limit(8)->get();
        return Inertia::render('User/Index', [
            'restaurants' => $restaurants,
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function restaurant($title)
    {
        $restaurant = Restaurant::where('slug', $title)->with('category', 'restaurant_images')->first();
        return Inertia::render('User/Restaurant', [
            'restaurant' => $restaurant,
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
    
    public function event()
    {
        $events = Event::with('restaurant', 'event_images')
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();
    
        return Inertia::render('User/Event', [
            'events' => $events, // ✅ key change here
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function storeApplication(Request $request)
    {
    
        RestaurantForm::create([
            'name' => $request->name,
            'address' => $request->address,
            'contacts' => $request->contacts,
        ]);
    
        return redirect()->back()->with('success', 'Application submitted!');
    }

    public function application()
    {
        return Inertia::render('User/Application');
    }
    
}
