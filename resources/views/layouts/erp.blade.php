<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Hôtelier — Pilotage Central</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light pb-5">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold text-warning" href="{{ route('erp.dashboard') }}">
                <i class="fa-solid fa-shield-halved me-2"></i>HOTEL ERP
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#erpNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="erpNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('erp.dashboard') ? 'active fw-bold text-warning' : '' }}"
                            href="{{ route('erp.dashboard') }}">
                            <i class="fa-solid fa-chart-pie me-1"></i>Tableau de Bord
                        </a>
                    </li>
                    @can('manage_reservations')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('erp.reservations.*') ? 'active fw-bold text-warning' : '' }}"
                                href="{{ route('erp.reservations.index') }}">
                                <i class="fa-solid fa-bed me-1"></i>Réservations & CRM
                            </a>
                        </li>
                    @endcan
                    @can('manage_billing')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('erp.invoices.*') ? 'active fw-bold text-warning' : '' }}"
                                href="{{ route('erp.invoices.index') }}">
                                <i class="fa-solid fa-file-invoice-dollar me-1"></i>Facturation
                            </a>
                        </li>
                    @endcan
                    @can('manage_stock')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('erp.stock.*') ? 'active fw-bold text-warning' : '' }}"
                                href="{{ route('erp.stock.index') }}">
                                <i class="fa-solid fa-boxes-stacked me-1"></i>Stock & Économat
                            </a>
                        </li>
                    @endcan
                    @can('manage_hr')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('erp.hr.*') ? 'active fw-bold text-warning' : '' }}"
                                href="{{ route('erp.hr.index') }}">
                                <i class="fa-solid fa-users-gear me-1"></i>RH & Paie
                            </a>
                        </li>
                    @endcan
                    @can('manage_users_rbac')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('erp.admin.*') ? 'active fw-bold text-warning' : '' }}"
                                href="{{ route('erp.admin.privileges') }}">
                                <i class="fa-solid fa-user-lock me-1"></i>Admin & Privilèges
                            </a>
                        </li>
                    @endcan
                </ul>

                <div class="d-flex align-items-center gap-3 text-white small">
                    <span>
                        <strong>{{ auth()->user()->name }}</strong>
                        <span
                            class="badge bg-warning text-dark ms-1">{{ auth()->user()->getRoleNames()->first() }}</span>
                    </span>
                    <a href="{{ route('public.home') }}" class="btn btn-sm btn-outline-light rounded-pill">Vitrine
                        Public</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
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
    </div>
</body>

</html>
