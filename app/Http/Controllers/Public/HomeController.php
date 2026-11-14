<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Hotel;

class HomeController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with('rooms')->get();

        return view('public.welcome', compact('hotels'));
    }

    public function roomsByHotel(Hotel $hotel)
    {
        return response()->json([
            'hotel' => $hotel,
            'rooms' => $hotel->rooms()->where('available_count', '>', 0)->get(),
        ]);
    }
}
