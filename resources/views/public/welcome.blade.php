@extends('layouts.public')

@section('content')
    <!-- Hero Section -->
    <section class="bg-azalai-dark text-white py-5">
        <div class="container py-4 text-center">
            <span class="badge rounded-pill bg-warning text-dark px-3 py-2 mb-3 text-uppercase fw-bold">
                L'Art de Vivre Africain
            </span>
            <h1 class="display-5 fw-bold mb-3">Réservez votre séjour d'exception en Afrique de l'Ouest</h1>
            <p class="lead text-white-50 mx-auto mb-4" style="max-width: 680px;">
                Abidjan, Bamako, Dakar, Cotonou et Ouagadougou. Confirmation immédiate et génération instantanée de votre
                facture officielle en XOF.
            </p>

            <!-- Formulaire de Réservation Directe -->
            <div class="card border-0 shadow-lg rounded-4 text-dark text-start mx-auto mt-4" style="max-width: 960px;">
                <div class="card-body p-4">
                    <form action="{{ route('public.bookings.store') }}" method="POST" class="row g-3">
                        @csrf
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Destination & Hôtel</label>
                            <select name="hotel_id" id="hotelSelect" class="form-select rounded-3" required>
                                @foreach($hotels as $hotel)
                                    <option value="{{ $hotel->id }}">{{ $hotel->name }} ({{ $hotel->city }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Catégorie de Chambre</label>
                            <select name="room_id" class="form-select rounded-3" required>
                                @foreach($hotels as $hotel)
                                    @foreach($hotel->rooms as $room)
                                        <option value="{{ $room->id }}">
                                            {{ $hotel->city }} — {{ $room->name }}
                                            ({{ number_format($room->price_per_night, 0, ',', ' ') }} XOF/nuit)
                                        </option>
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-uppercase text-muted">Arrivée</label>
                            <input type="date" name="check_in" class="form-control rounded-3"
                                value="{{ now()->addDay()->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold text-uppercase text-muted">Départ</label>
                            <input type="date" name="check_out" class="form-control rounded-3"
                                value="{{ now()->addDays(4)->format('Y-m-d') }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Nom & Prénom du Client</label>
                            <input type="text" name="client_name" class="form-control rounded-3"
                                value="{{ auth()->user()->name ?? '' }}" placeholder="Ex: Fatoumata Touré" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Email (Facture PDF)</label>
                            <input type="email" name="client_email" class="form-control rounded-3"
                                value="{{ auth()->user()->email ?? '' }}" placeholder="client@exemple.com" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Téléphone Mobile</label>
                            <input type="tel" name="client_phone" class="form-control rounded-3"
                                value="{{ auth()->user()->phone ?? '' }}" placeholder="+223 70 00 00 00" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Voyageurs</label>
                            <select name="guests_summary" class="form-select rounded-3">
                                <option value="1 Adulte · 1 Chambre">1 Adulte · 1 Chambre</option>
                                <option value="2 Adultes · 1 Chambre" selected>2 Adultes · 1 Chambre</option>
                                <option value="Famille · Suite">Famille · Suite</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-uppercase text-muted">Option VIP</label>
                            <select name="extra_option" class="form-select rounded-3">
                                <option value="standard">Petit-déjeuner Buffet inclus (Offert)</option>
                                <option value="airport">Transfert Aéroport VIP (+15 000 XOF)</option>
                                <option value="halfboard">Demi-Pension Gastronomique (+25 000 XOF)</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-terracotta w-100 py-2 fw-bold rounded-3 shadow">
                                <i class="fa-solid fa-file-invoice me-2"></i>Confirmer & Générer Facture
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Destinations Phares -->
    <section id="destinations" class="container py-5">
        <div class="text-center mb-5">
            <span class="text-uppercase small fw-bold" style="color: #C87D55;">Collection Ouest-Africaine</span>
            <h2 class="fw-bold mt-1">Nos Établissements Hotels</h2>
        </div>

        <div class="row g-4">
            @foreach($hotels as $hotel)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <img src="{{ $hotel->image_url }}" class="card-img-top" style="height: 220px; object-fit: cover;"
                            alt="{{ $hotel->name }}">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-dark text-warning">{{ $hotel->badge }}</span>
                                <small class="text-muted fw-bold">{{ $hotel->country }}</small>
                            </div>
                            <h5 class="card-title fw-bold">{{ $hotel->name }}</h5>
                            <p class="card-text text-muted small flex-grow-1">{{ $hotel->description }}</p>
                            <div class="pt-3 border-top d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block">À partir de</small>
                                    <strong style="color: #C87D55;">{{ number_format($hotel->min_price, 0, ',', ' ') }}
                                        XOF</strong>
                                </div>
                                <a href="#hotelSelect" class="btn btn-sm btn-dark rounded-pill px-3">Choisir</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endsection
