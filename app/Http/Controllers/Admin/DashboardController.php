<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Payment;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'total_users' => User::count(),
            'total_restaurants' => Restaurant::count(),

            'total_revenue' => number_format(
                Payment::sum('price'),
                2,
                '.',
                ''
            ),

            // Show 5 most recent user accounts
            'recent_users' => User::latest()
                ->take(5)
                ->get(['email', 'created_at']),

            // Count users registered in the last 7 days
            'new_signups_last_7_days' => User::where('created_at', '>=', Carbon::now()->subDays(7))->count(),

            // Revenue grouped per week
            'revenue_per_week' => Payment::selectRaw('YEAR(created_at) as year, WEEK(created_at, 1) as week, SUM(price) as total')
                ->groupBy('year', 'week')
                ->orderBy('year')
                ->orderBy('week')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [
                        "Week {$item->week}" => number_format($item->total, 2, '.', '')
                    ];
                }),

            // Role distribution
            'user_roles' => [
                'Admin' => User::where('isAdmin', true)->count(),
                'Restaurant Owner' => User::where('isRestaurant', true)->count(),
                'User' => User::where('isAdmin', false)->where('isRestaurant', false)->count(),
            ],
        ]);
    }
}