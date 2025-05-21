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
use App\Models\Payment;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class UserController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::with('categories', 'restaurant_images')
            ->where('published', 0)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();
    
        $events = Event::with('restaurant', 'event_images')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();
    
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
        $restaurant = Restaurant::with(['categories', 'restaurant_images', 'menus'])->findOrFail($id);
    
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
        $restaurants = Restaurant::with('categories', 'restaurant_images')
            ->where('published', 0)
            ->orderBy('id', 'desc')
            ->get();
    
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
        $events = Event::with('restaurant', 'event_images')->orderBy('id', 'desc')->get();
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
            'seats' => 'required|integer|min:1|max:20',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|string',
            'price' => 'required|numeric',
        ]);
        
        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'restaurant_id' => $validated['restaurant_id'],
            'table_number' => $validated['table_number'],
            'seats' => $validated['seats'],
            'reservation_date' => $validated['date'],
            'time' => $validated['time'],
            'price' => $validated['price'],
            'status' => 'pending',  
        ]);        
    
        return response()->json(['reservation_id' => $reservation->id]);
    }

    public function getTableReservations(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'table_number' => 'required|integer',
            'date' => 'required|date',
        ]);

        $reservations = Reservation::where('restaurant_id', $request->restaurant_id)
            ->where('table_number', $request->table_number)
            ->where('reservation_date', $request->date)
            ->pluck('time');

        return response()->json($reservations);
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

    public function cancelReservation($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->status = 'cancelled';
        $reservation->save();
    
        return response()->json(['success' => true]);
    }
    
    public function payForReservation($id)
    {
        $reservation = Reservation::with('restaurant')->findOrFail($id);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Table Reservation at ' . $reservation->restaurant->title,
                    ],
                    'unit_amount' => $reservation->price * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('reservations.success', ['reservation' => $reservation->id]),
            'cancel_url' => route('reservations.cancel', ['reservation' => $reservation->id]),
        ]);

        return redirect($session->url);
    }

    public function paymentSuccess($id)
    {
        $reservation = Reservation::findOrFail($id);

        // Update reservation status
        $reservation->update(['status' => 'paid']);

        // Create payment
        Payment::create([
            'reservation_id' => $reservation->id,
            'price' => $reservation->price,
            'status' => 'paid',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('user.reservations')->with('success', 'Reservation paid successfully.');
    }
    
    public function paymentCancel($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => 'cancelled']);

        return redirect()->route('user.reservations')->with('error', 'Payment was cancelled.');
    }
}
