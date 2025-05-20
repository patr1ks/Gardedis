<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Inertia\Inertia;

class OwnerPaymentController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
    
        $payments = Payment::with(['user', 'reservation.restaurant'])
            ->whereHas('reservation.restaurant', function ($query) use ($userId) {
                $query->where('owner', $userId);
            })
            ->latest()
            ->get();
    
        return Inertia::render('RestaurantOwner/Payment/Index', [
            'payments' => $payments,
        ]);
    }    

    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();

        return redirect()->route('restaurantOwner.payments.index')
            ->with('success', 'Payment deleted successfully');
    }

    public function showData($id)
    {
        $payment = Payment::with(['user', 'reservation.restaurant'])->findOrFail($id);

        return response()->json([
            'payment' => $payment
        ]);
    }
}
