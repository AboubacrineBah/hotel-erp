<?php

namespace App\Http\Controllers\ERP;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['booking.hotel', 'booking.room'])
            ->latest('issued_at')
            ->paginate(15);

        return view('erp.billing.index', compact('invoices'));
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['booking.hotel', 'booking.room']);

        // Vérification : autorisé si staff facturation, propriétaire de la réservation, ou session récente
        $user = auth()->user();
        $isStaff = $user && $user->can('manage_billing');
        $isOwner = $user && $invoice->booking->user_id === $user->id;
        $isRecentGuestBooking = session('last_invoice_number') === $invoice->invoice_number;

        abort_unless($isStaff || $isOwner || $isRecentGuestBooking, 403, 'Accès non autorisé à cette facture.');

        $pdf = Pdf::loadView('erp.billing.invoice_pdf', compact('invoice'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("{$invoice->invoice_number}.pdf");
    }
}
