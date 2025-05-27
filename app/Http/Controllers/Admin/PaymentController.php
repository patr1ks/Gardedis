<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentController extends Controller
{
    public function index()
    {
        // Eager load user and reservation + restaurant
        $payments = Payment::with(['user', 'reservation.restaurant'])
            ->latest()
            ->get();

        return Inertia::render('Admin/Payment/Index', [
            'payments' => $payments,
        ]);
    }

    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment deleted successfully');
    }

    public function showData($id)
    {
        $payment = Payment::with(['user', 'reservation.restaurant'])->findOrFail($id);

        return response()->json([
            'payment' => $payment
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled'
        ]);
    
        $payment = Payment::findOrFail($id);
        $payment->status = $request->status;
    
        $payment->save();
    
        return response()->json(['success' => true]);
    }    

}
