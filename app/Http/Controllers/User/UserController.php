<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Application;
use App\Models\Restaurant;
use App\Models\Event;
use App\Models\RestaurantForm;
use App\Models\Contact;
use App\Models\Reservation;

class UserController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with('categories', 'restaurant_images')->orderBy('id', 'desc')->limit(5)->get();
        $events = Event::with('restaurant', 'event_images')->orderBy('id', 'desc')->limit(5)->get();
    
        return Inertia::render('User/Index', [
            'restaurants' => $restaurants,
            'events' => $events,
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
    

    public function restaurant($id)
    {
        $restaurant = Restaurant::with('categories', 'restaurant_images')->findOrFail($id);
    
        return Inertia::render('User/SelectedRestaurant/Index', [
            'restaurant' => $restaurant,
            'layout' => $restaurant->layout_json ? json_decode($restaurant->layout_json) : null,
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }      

    public function restaurants()
    {
        $restaurants = Restaurant::with('categories', 'restaurant_images')->orderBy('id', 'desc')->limit(8)->get();
        return Inertia::render('User/Restaurant', [
            'restaurants' => $restaurants,
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }
    
    public function events()
    {
        $events = Event::with('restaurant', 'event_images')->orderBy('id', 'desc')->limit(8)->get();
        return Inertia::render('User/Event', [
            'events' => $events, 
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
    }

    public function event($id)
    {
        $event = Event::with('restaurant', 'event_images')->findOrFail($id);

        return Inertia::render('User/SelectedEvent/Index', [
            'event' => $event,
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

    public function storeContacts(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email'],
            'telephone' => ['required', 'digits:8'],
            'message' => ['required', 'string'],
        ]);
    
        Contact::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'message' => $request->message,
        ]);        
    
        return redirect()->back()->with('success', 'Message submitted!');
    }

    public function contacts()
    {
        return Inertia::render('User/Contacts');
    }

    public function about()
    {
        return Inertia::render('User/About');
    }

    public function storeReservation(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'table_number' => 'required|integer',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|string',
            'price' => 'required|numeric',
        ]);
    
        $reservation = Reservation::create([
            'restaurant_id' => $validated['restaurant_id'],
            'table_number' => $validated['table_number'],
            'date' => $validated['date'],
            'time' => $validated['time'],
            'price' => $validated['price'],
            'status' => 'pending',
            'created_by' => auth()->id(),
        ]);
    
        return redirect()->back()->with('success', 'Reservation successful!');
    }       

    public function reservations()
    {
        $reservations = Reservation::with('restaurant')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
    
        return Inertia::render('User/Reservations', [
            'reservations' => $reservations
        ]);
    }
    
}
