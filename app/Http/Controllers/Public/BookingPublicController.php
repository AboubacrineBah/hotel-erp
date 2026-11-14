<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingPublicController extends Controller
{
    public function store(StoreBookingRequest $request)
    {
        $room = Room::findOrFail($request->room_id);

        if ($room->available_count < 1) {
            return back()->withErrors(['room_id' => 'Cette catégorie de chambre n\'est plus disponible.']);
        }

        $checkIn = Carbon::parse($request->check_in);
        $checkOut = Carbon::parse($request->check_out);
        $nights = max(1, $checkIn->diffInDays($checkOut));

        // Options VIP supplémentaires
        $extraPrice = 0;
        $extraName = 'Petit-déjeuner Buffet inclus';
        if ($request->extra_option === 'airport') {
            $extraPrice = 15000;
            $extraName = 'Transfert Aéroport VIP Aller-Retour';
        } elseif ($request->extra_option === 'halfboard') {
            $extraPrice = 25000;
            $extraName = 'Forfait Demi-Pension Gastronomique';
        }

        $totalTTC = ($room->price_per_night * $nights) + $extraPrice;
        $amountHT = round($totalTTC / 1.18, 2);
        $tvaAmount = round($totalTTC - $amountHT, 2);

        $invoice = DB::transaction(function () use ($request, $room, $nights, $extraName, $extraPrice, $totalTTC, $amountHT, $tvaAmount) {
            // 1. Décrémenter la disponibilité
            $room->decrement('available_count');

            // 2. Créer la réservation CRM
            $booking = Booking::create([
                'reference' => 'AZL-'.random_int(1000, 9999),
                'user_id' => Auth::id(),
                'hotel_id' => $request->hotel_id,
                'room_id' => $room->id,
                'client_name' => $request->client_name,
                'client_email' => $request->client_email,
                'client_phone' => $request->client_phone,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'nights' => $nights,
                'guests_summary' => $request->guests_summary,
                'extra_option_name' => $extraName,
                'extra_option_price' => $extraPrice,
                'total_ttc' => $totalTTC,
                'status' => 'Confirmée',
            ]);

            // 3. Créditer les points Nomad si client connecté
            if (Auth::check()) {
                Auth::user()->increment('nomad_points', (int) floor($totalTTC / 100));
            }

            // 4. Générer la facture normalisée
            return Invoice::create([
                'invoice_number' => 'FAC-'.date('Y').'-'.random_int(1000, 9999),
                'booking_id' => $booking->id,
                'amount_ht' => $amountHT,
                'tva_rate' => 18.00,
                'tva_amount' => $tvaAmount,
                'amount_ttc' => $totalTTC,
                'payment_status' => 'Payée',
                'issued_at' => now(),
            ]);
        });

        session(['last_invoice_number' => $invoice->invoice_number]);

        return redirect()
            ->route('invoices.pdf', $invoice->invoice_number)
            ->with('success', "Réservation confirmée ! Facture {$invoice->invoice_number} générée.");
    }

    public function myBookings()
    {
        $bookings = Booking::with(['hotel', 'room', 'invoice'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('public.my_bookings', compact('bookings'));
    }
}
