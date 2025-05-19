<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Image;
use App\Models\Restaurant;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OwnerEventController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
    
        // Get restaurants owned by the current user
        $restaurants = Restaurant::where('owner', $userId)->get();
    
        // Get only events linked to the user's restaurants
        $events = Event::with('restaurant', 'event_images')
            ->whereIn('restaurant_id', $restaurants->pluck('id'))
            ->get();
    
        return Inertia::render('RestaurantOwner/Event/Index', [
            'events' => $events,
            'restaurants' => $restaurants
        ]);
    }   

    public function store(Request $request)
    {
    
        $event = new Event();
        $event->title = $request->title;
        $event->description = $request->description;
        $event->event_date = Carbon::createFromFormat('d.m.Y', $request->event_date)->format('Y-m-d');
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
    
        return redirect()->route('restaurantOwner.events.index')->with('success', 'Event created successfully');
    }

    public function update(Request $request, $id)
{
    $event = Event::findOrFail($id);

    $event->title = $request->title;
    $event->description = $request->description;
    $event->event_date = Carbon::createFromFormat('d.m.Y', $request->event_date)->format('Y-m-d');
    $event->restaurant_id = $request->restaurant_id;
    $event->save();

    // Handle new image uploads
    if ($request->hasFile('event_images')) {
        foreach ($request->file('event_images') as $image) {
            $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->move('event_images', $uniqueName);

            \App\Models\Image::create([
                'event_id' => $event->id,
                'restaurant_id' => null,
                'image' => 'event_images/' . $uniqueName,
            ]);
        }
    }

    return redirect()->route('restaurantOwner.events.index')->with('success', 'Event updated successfully');
}

    

    public function deleteImage($id)
    {
        $restaurantImage = Image::find($id);
            $image = Image::where('id', $id)->delete();
            return redirect()->route('restaurantOwner.events.index')->with('success', 'Image deleted successfully');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id)->delete();
        return redirect()->route('restaurantOwner.events.index')->with('success', 'Event deleted successfully');
    }

    public function showData($id)
    {
        $event = Event::with('restaurant', 'event_images')->findOrFail($id);
        return response()->json(['event' => $event]);
    }
}
