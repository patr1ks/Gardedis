<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Event;
use App\Models\Restaurant;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RestaurantDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $restaurant = Restaurant::where('owner', $userId)->first();

        if (!$restaurant) {
            return response()->json([
                'error' => 'Restaurant not found for this user.'
            ], 404);
        }

        $totalRevenue = DB::table('payments')
            ->join('reservations', 'payments.reservation_id', '=', 'reservations.id')
            ->where('reservations.restaurant_id', $restaurant->id)
            ->sum('payments.price');

        $weeklyRevenue = DB::table('payments')
            ->join('reservations', 'payments.reservation_id', '=', 'reservations.id')
            ->selectRaw('WEEK(payments.created_at) as week, SUM(payments.price) as total')
            ->where('reservations.restaurant_id', $restaurant->id)
            ->groupBy('week')
            ->pluck('total', 'week');

        $reservationStatuses = Reservation::selectRaw('status, COUNT(*) as count')
            ->where('restaurant_id', $restaurant->id)
            ->groupBy('status')
            ->pluck('count', 'status');

        return response()->json([
            'restaurant_name' => $restaurant->title,
            'is_published' => $restaurant->published,
            'total_menu_items' => Menu::where('restaurant_id', $restaurant->id)->count(),
            'total_events' => Event::where('restaurant_id', $restaurant->id)->count(),
            'total_payments' => number_format($totalRevenue, 2, '.', ''),
            'has_layout' => !is_null($restaurant->layout_json),
            'weekly_revenue' => $weeklyRevenue,
            'reservation_statuses' => $reservationStatuses,
        ]);
    }
}