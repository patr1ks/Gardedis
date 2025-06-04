<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Reservation;

class OwnerPaymentController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
    
        $reservations = Reservation::with(['user', 'restaurant'])
            ->whereHas('restaurant', function ($query) use ($userId) {
                $query->where('owner', $userId);
            })
            ->latest()
            ->get();
    
        return Inertia::render('RestaurantOwner/Payment/Index', [
            'reservations' => $reservations,
        ]);
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);
    
        $reservation = Reservation::findOrFail($id);
        $reservation->status = $request->status;
        $reservation->save();
    
        return response()->json(['message' => 'Status updated']);
    }   

    public function destroy($id)
    {
        Reservation::findOrFail($id)->delete();

        return redirect()->route('restaurantOwner.payments.index')
            ->with('success', 'Payment deleted successfully');
    }
}
