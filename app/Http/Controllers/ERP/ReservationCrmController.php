<?php

namespace App\Http\Controllers\ERP;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use Illuminate\Http\Request;

class ReservationCrmController extends Controller
{
    public function index(Request $request)
    {
        $hotelSlug = $request->query('hotel', 'all');
        $hotels = Hotel::all();

        $query = Booking::with(['hotel', 'room', 'invoice'])->latest();
        if ($hotelSlug !== 'all') {
            $query->whereHas('hotel', fn ($q) => $q->where('slug', $hotelSlug));
        }

        $bookings = $query->paginate(15);

        return view('erp.reservations.index', compact('bookings', 'hotels', 'hotelSlug'));
    }

    public function checkIn(Booking $booking)
    {
        $booking->update(['status' => 'Check-in Effectué']);

        return back()->with('success', "Check-in validé pour {$booking->client_name} (Dossier {$booking->reference}).");
    }
}
