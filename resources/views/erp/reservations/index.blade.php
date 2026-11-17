@extends('layouts.erp')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Réservations & CRM</h2>
        <p class="text-muted mb-0 small">Gestion des dossiers clients et check-in</p>
    </div>

    <form method="GET" action="{{ route('erp.reservations.index') }}" class="d-flex gap-2 align-items-center">
        <select name="hotel" class="form-select form-select-sm" style="min-width: 180px" onchange="this.form.submit()">
            <option value="all" @selected(($hotelSlug ?? 'all') === 'all')>Tous les hôtels</option>
            @foreach($hotels as $hotel)
                <option value="{{ $hotel->slug }}" @selected(($hotelSlug ?? '') === $hotel->slug)>
                    {{ $hotel->name }}
                </option>
            @endforeach
        </select>
    </form>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        @if($bookings->isEmpty())
            <div class="alert alert-info mb-0 rounded-3">Aucune réservation trouvée.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Réf.</th>
                            <th>Client</th>
                            <th>Hôtel / Chambre</th>
                            <th>Arrivée → Départ</th>
                            <th class="text-end">Total</th>
                            <th>Statut</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                            <tr>
                                <td class="fw-semibold">{{ $booking->reference }}</td>
                                <td>
                                    {{ $booking->client_name }}
                                    <div class="small text-muted">{{ $booking->client_phone }}</div>
                                </td>
                                <td>
                                    {{ $booking->hotel?->name ?? '—' }}
                                    <div class="small text-muted">{{ $booking->room?->name ?? '—' }}</div>
                                </td>
                                <td class="small">
                                    {{ $booking->check_in?->format('d/m/Y') }}
                                    → {{ $booking->check_out?->format('d/m/Y') }}
                                </td>
                                <td class="text-end">{{ number_format($booking->total_ttc, 0, ',', ' ') }} XOF</td>
                                <td>
                                    <span class="badge bg-{{ $booking->status === 'Confirmée' ? 'success' : 'primary' }}">
                                        {{ $booking->status }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    @if(in_array($booking->status, ['Confirmée', 'En attente']))
                                        <form action="{{ route('erp.reservations.checkin', $booking) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill"
                                                onclick="return confirm('Valider le check-in de {{ $booking->client_name }} ?')">
                                                <i class="fa-solid fa-right-to-bracket me-1"></i>Check-in
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $bookings->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
