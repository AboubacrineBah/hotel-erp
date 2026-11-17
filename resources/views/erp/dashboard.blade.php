@extends('layouts.erp')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
    <div>
        <h2 class="fw-bold mb-1">Tableau de bord</h2>
        <p class="text-muted mb-0 small">Pilotage central — Groupe Hôtels & Resorts</p>
    </div>

    {{-- Filtre hôtel --}}
    <form method="GET" action="{{ route('erp.dashboard') }}" class="d-flex gap-2 align-items-center">
        <label class="small text-muted mb-0">Établissement</label>
        <select name="hotel" class="form-select form-select-sm" style="min-width: 180px" onchange="this.form.submit()">
            <option value="all" @selected($hotelSlug === 'all')>Tous les hôtels</option>
            @foreach($hotels as $hotel)
                <option value="{{ $hotel->slug }}" @selected($hotelSlug === $hotel->slug)>
                    {{ $hotel->name }} ({{ $hotel->city }})
                </option>
            @endforeach
        </select>
    </form>
</div>

{{-- KPIs --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="text-muted small mb-1"><i class="fa-solid fa-calendar-check me-1"></i>Réservations</div>
                <div class="fs-3 fw-bold">{{ $activeBookingsCount }}</div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="text-muted small mb-1"><i class="fa-solid fa-coins me-1"></i>CA TTC</div>
                <div class="fs-3 fw-bold">
                    @if(!is_null($totalRevenue))
                        {{ number_format($totalRevenue, 0, ',', ' ') }} <span class="fs-6">XOF</span>
                    @else
                        <span class="text-muted fs-6">Non autorisé</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="text-muted small mb-1"><i class="fa-solid fa-boxes-stacked me-1"></i>Alertes stock</div>
                <div class="fs-3 fw-bold {{ $lowStockCount > 0 ? 'text-danger' : 'text-success' }}">
                    {{ $lowStockCount }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body">
                <div class="text-muted small mb-1"><i class="fa-solid fa-user-tie me-1"></i>Personnel actif</div>
                <div class="fs-3 fw-bold">{{ $activeStaffCount }} <span class="fs-6 text-muted">/ {{ $totalStaffCount }}</span></div>
            </div>
        </div>
    </div>
</div>

{{-- Dernières réservations --}}
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Dernières réservations</h5>
            @can('manage_reservations')
                <a href="{{ route('erp.reservations.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                    Voir tout
                </a>
            @endcan
        </div>

        @if($bookings->isEmpty())
            <div class="alert alert-info mb-0 rounded-3">Aucune réservation pour le moment.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Réf.</th>
                            <th>Client</th>
                            <th>Hôtel / Chambre</th>
                            <th>Dates</th>
                            <th class="text-end">Total TTC</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings->take(10) as $booking)
                            <tr>
                                <td class="fw-semibold">{{ $booking->reference }}</td>
                                <td>
                                    {{ $booking->client_name }}
                                    <div class="small text-muted">{{ $booking->client_email }}</div>
                                </td>
                                <td>
                                    {{ $booking->hotel?->name ?? '—' }}
                                    <div class="small text-muted">{{ $booking->room?->name ?? '—' }}</div>
                                </td>
                                <td class="small">
                                    {{ $booking->check_in?->format('d/m/Y') }}
                                    → {{ $booking->check_out?->format('d/m/Y') }}
                                    <div class="text-muted">{{ $booking->nights }} nuit(s)</div>
                                </td>
                                <td class="text-end fw-semibold">
                                    {{ number_format($booking->total_ttc, 0, ',', ' ') }} XOF
                                </td>
                                <td>
                                    @php
                                        $badge = match($booking->status) {
                                            'Confirmée' => 'bg-success',
                                            'Check-in Effectué', 'Checked-in', 'En cours' => 'bg-primary',
                                            'Annulée' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp
                                    <span class="badge {{ $badge }}">{{ $booking->status }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
