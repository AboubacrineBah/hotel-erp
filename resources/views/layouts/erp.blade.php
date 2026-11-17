<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Hôtelier — Pilotage Central</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light pb-5">
    <div class="d-flex flex-column flex-lg-row min-vh-100">

        <!-- BARRE DE NAVIGATION LATÉRALE (GAUCHE) -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm erp-sidebar flex-lg-column align-items-stretch p-3"
            aria-label="Navigation ERP">
            <!-- Logo + Bouton Hamburger Mobile -->
            <div
                class="d-flex justify-content-between align-items-center w-100 mb-lg-4 pb-lg-2 border-bottom border-white border-opacity-25">
                <a class="navbar-brand fw-bold text-warning" href="{{ route('erp.dashboard') }}">
                    <i class="fa-solid fa-shield-halved me-2"></i>HOTEL ERP
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#erpNav"
                    aria-controls="erpNav" aria-expanded="false" aria-label="Afficher le menu">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <!-- Contenu pliable (Liens + Profil utilisateur) -->
            <div class="collapse navbar-collapse flex-lg-column align-items-stretch w-100 mt-3 mt-lg-0" id="erpNav">
                <!-- Liens de navigation empilés verticalement -->
                <ul class="navbar-nav flex-column w-100 gap-1 mb-auto">
                    <li class="nav-item">
                        <a class="nav-link rounded px-3 py-2 {{ request()->routeIs('erp.dashboard') ? 'active fw-bold text-warning bg-white bg-opacity-10' : '' }}"
                            href="{{ route('erp.dashboard') }}">
                            <i class="fa-solid fa-chart-pie me-2 fa-fw"></i>Tableau de Bord
                        </a>
                    </li>
                    @can('manage_reservations')
                        <li class="nav-item">
                            <a class="nav-link rounded px-3 py-2 {{ request()->routeIs('erp.reservations.*') ? 'active fw-bold text-warning bg-white bg-opacity-10' : '' }}"
                                href="{{ route('erp.reservations.index') }}">
                                <i class="fa-solid fa-bed me-2 fa-fw"></i>Réservations & CRM
                            </a>
                        </li>
                    @endcan
                    @can('manage_billing')
                        <li class="nav-item">
                            <a class="nav-link rounded px-3 py-2 {{ request()->routeIs('erp.invoices.*') ? 'active fw-bold text-warning bg-white bg-opacity-10' : '' }}"
                                href="{{ route('erp.invoices.index') }}">
                                <i class="fa-solid fa-file-invoice-dollar me-2 fa-fw"></i>Facturation
                            </a>
                        </li>
                    @endcan
                    @can('manage_stock')
                        <li class="nav-item">
                            <a class="nav-link rounded px-3 py-2 {{ request()->routeIs('erp.stock.*') ? 'active fw-bold text-warning bg-white bg-opacity-10' : '' }}"
                                href="{{ route('erp.stock.index') }}">
                                <i class="fa-solid fa-boxes-stacked me-2 fa-fw"></i>Stock & Économat
                            </a>
                        </li>
                    @endcan
                    @can('manage_hr')
                        <li class="nav-item">
                            <a class="nav-link rounded px-3 py-2 {{ request()->routeIs('erp.hr.*') ? 'active fw-bold text-warning bg-white bg-opacity-10' : '' }}"
                                href="{{ route('erp.hr.index') }}">
                                <i class="fa-solid fa-users-gear me-2 fa-fw"></i>RH & Paie
                            </a>
                        </li>
                    @endcan
                    @can('manage_users_rbac')
                        <li class="nav-item">
                            <a class="nav-link rounded px-3 py-2 {{ request()->routeIs('erp.admin.*') ? 'active fw-bold text-warning bg-white bg-opacity-10' : '' }}"
                                href="{{ route('erp.admin.privileges') }}">
                                <i class="fa-solid fa-user-lock me-2 fa-fw"></i>Admin & Privilèges
                            </a>
                        </li>
                    @endcan
                </ul>
                <!-- Bloc Utilisateur & Bouton Vitrine en bas de la barre latérale -->
                <div
                    class="d-flex flex-column gap-2 text-white small pt-3 mt-3 border-top border-white border-opacity-25">
                    <div>
                        <div class="fw-bold text-truncate">
                            <span>
                                <strong>{{ auth()->user()->name }}</strong>
                            </span>
                        </div>
                        <div class="mt-1 d-flex flex-wrap gap-1">
                            <span
                                class="badge bg-warning text-dark">{{ auth()->user()->getRoleNames()->first() }}</span>
                            <span
                                class="badge bg-light text-dark">{{ auth()->user()->hotel?->name ?? 'Tous les hôtels' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('public.home') }}" class="btn btn-sm btn-outline-light rounded-pill w-100 mt-1">
                        <i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Vitrine Public
                    </a>
                </div>
            </div>

        </nav>
        <!-- CONTENU DU TABLEAU DE BORD (DROITE) -->
        <main class="flex-grow-1 p-4 bg-light">
            @if(session('success'))
                <div class="alert alert-success rounded-3 shadow-sm">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger rounded-3 shadow-sm">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>

</html>
