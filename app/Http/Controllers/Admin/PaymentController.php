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
        $payments = Payment::get();
        return Inertia::render('Admin/Payment/Index', ['payment' => $payments]);
    }    

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id)->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Payment deleted successfully');
    }

    public function showData($id)
    {
        $payment = Payment::with('user', 'restaurant')->findOrFail($id); // adjust relationships if needed
        return response()->json(['payment' => $payment]);
    }

    
}
