<?php

namespace App\Http\Controllers\ERP;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Employee;
use App\Models\Hotel;
use App\Models\StockItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $hotelSlug = $request->query('hotel', 'all');
        $hotels = Hotel::all();

        $bookingsQuery = Booking::with(['hotel', 'room', 'invoice']);
        if ($hotelSlug !== 'all') {
            $bookingsQuery->whereHas('hotel', fn ($q) => $q->where('slug', $hotelSlug));
        }

        $bookings = $bookingsQuery->latest()->get();

        // KPIs calculés selon privilèges RBAC
        $totalRevenue = $user->can('view_revenue')
            ? $bookings->sum('total_ttc')
            : null;

        $activeBookingsCount = $bookings->count();
        $lowStockCount = StockItem::whereColumn('quantity', '<=', 'alert_threshold')->count();
        $activeStaffCount = Employee::where('presence_status', 'En poste')->count();
        $totalStaffCount = Employee::count();

        return view('erp.dashboard', compact(
            'hotels',
            'hotelSlug',
            'bookings',
            'totalRevenue',
            'activeBookingsCount',
            'lowStockCount',
            'activeStaffCount',
            'totalStaffCount'
        ));
    }
}
