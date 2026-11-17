@extends('layouts.public')

@section('title', 'Mes Séjours | '.config('hotel.group_name'))

@section('content')
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-2">
            <h1 class="h3 fw-bold font-display mb-0">Mes Séjours</h1>
            <span class="badge bg-dark text-warning fs-6 px-3 py-2">
                <i class="fa-solid fa-star me-1" aria-hidden="true"></i>Club Nomad : {{ number_format(auth()->user()->nomad_points, 0, ',', ' ') }} points
            </span>
        </div>

        <section id="factures" aria-labelledby="titre-sejours">
            <h2 id="titre-sejours" class="visually-hidden">Liste de mes réservations et factures</h2>

            @if($bookings->isEmpty())
                <div class="alert alert-info">
                    Vous n'avez pas encore de réservation.
                    <a href="{{ route('public.home') }}#reservation" class="alert-link">Réserver un séjour</a>.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle bg-white shadow-sm rounded-4">
                        <caption class="visually-hidden">Réservations et factures de {{ auth()->user()->name }}</caption>
                        <thead class="table-light small text-uppercase">
                            <tr>
                                <th scope="col">Dossier</th>
                                <th scope="col">Hôtel</th>
                                <th scope="col">Séjour</th>
                                <th scope="col">Statut</th>
                                <th scope="col" class="text-end">Total à payer</th>
                                <th scope="col" class="text-end">Facture</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <th scope="row">{{ $booking->reference }}</th>
                                    <td>{{ $booking->hotel->name }}<br><small class="text-muted">{{ $booking->room->name }}</small></td>
                                    <td>{{ $booking->check_in->format('d/m/Y') }} → {{ $booking->check_out->format('d/m/Y') }}<br>
                                        <small class="text-muted">{{ $booking->nights }} nuit(s) · {{ $booking->guests_count }} voyageur(s)</small></td>
                                    <td>
                                        <span class="badge {{ $booking->status === 'Annulée' ? 'bg-danger' : 'bg-success' }}">{{ $booking->status }}</span>
                                    </td>
                                    <td class="text-end">
                                        @if($booking->invoice)
                                            {{ number_format($booking->invoice->total_due, 0, ',', ' ') }} XOF<br>
                                            <small class="badge {{ match($booking->invoice->payment_status) { 'Payée' => 'bg-success', 'Annulée' => 'bg-secondary', default => 'bg-warning text-dark' } }}">
                                                {{ $booking->invoice->payment_status }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        @if($booking->invoice)
                                            <a href="{{ route('invoices.pdf', $booking->invoice->invoice_number) }}" class="btn btn-sm btn-outline-dark"
                                                aria-label="Télécharger la facture {{ $booking->invoice->invoice_number }}">
                                                <i class="fa-solid fa-file-pdf me-1" aria-hidden="true"></i>PDF
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </section>
    </div>
@endsection
