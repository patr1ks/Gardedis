<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use Inertia\Inertia;
use App\Models\Restaurant;
use App\Models\Image;
use Illuminate\Support\Str;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('restaurant')->get();
        $restaurants = Restaurant::get();
        return Inertia::render('Admin/Event/Index', ['events' => $events, 'restaurants' => $restaurants]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            'restaurant_id' => 'required',
        ]);
    
        $event = new Event();
        $event->title = $request->title;
        $event->description = $request->description;
        $event->price = $request->price;
        $event->restaurant_id = $request->restaurant_id;
        $event->save();
    
        // Check if event has images
        if ($request->hasFile('event_images')) {
            $eventImages = $request->file('event_images');
            foreach ($eventImages as $image) {
                // Generate unique name for image
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                // Store image in public folder
                $image->move('event_images', $uniqueName);
                // Create event image record
                Image::create([
                    'event_id' => $event->id,
                    'restaurant_id' => null,
                    'image' => 'event_images/' . $uniqueName,
                ]);
            }
        }
    
        return redirect()->route('admin.events.index')->with('success', 'Event created successfully');
    }
    

    public function deleteImage($id)
    {
        $restaurantImage = Image::find($id);
            $image = Image::where('id', $id)->delete();
            return redirect()->route('admin.events.index')->with('success', 'Image deleted successfully');
    }
}
