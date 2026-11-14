<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Hôtels & Resorts | L\'Hospitalité Africaine')</title>
    <link rel="manifest" href="{{ asset('build/manifest.webmanifest') }}">
    <meta name="theme-color" content="#0B192C">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        .bg-azalai-dark {
            background-color: #0B192C !important;
        }

        .text-azalai-gold {
            color: #D4AF37 !important;
        }

        .btn-terracotta {
            background-color: #C87D55;
            color: #fff;
            border: none;
        }

        .btn-terracotta:hover {
            background-color: #B36A44;
            color: #fff;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">

    <!-- Navbar Visiteur Public -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-azalai-dark sticky-top shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand fw-bold tracking-widest text-azalai-gold" href="{{ route('public.home') }}">
                <i class="fa-solid fa-crown me-2"></i>GROUPE <span class="fs-6 fw-light text-white-50">Hôtels &
                    Resorts</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#publicNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="publicNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="#destinations">Destinations</a></li>
                    <li class="nav-item"><a class="nav-link" href="#experiences">Expériences</a></li>
                    <li class="nav-item"><a class="nav-link" href="#nomad-club">Club Nomad</a></li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link text-azalai-gold fw-semibold" href="{{ route('client.bookings') }}">
                                <i class="fa-solid fa-suitcase me-1"></i>Mes Séjours
                            </a>
                        </li>
                    @endauth
                </ul>

                <div class="d-flex align-items-center gap-2">
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-terracotta rounded-pill px-4 fw-bold btn-sm">
                            <i class="fa-regular fa-user me-1"></i>Connexion / Espace Pro
                        </a>
                    @else
                        @can('access_erp')
                            <a href="{{ route('erp.dashboard') }}"
                                class="btn btn-outline-warning rounded-pill px-3 fw-bold btn-sm">
                                <i class="fa-solid fa-shield-halved me-1"></i>Portail ERP
                            </a>
                        @endcan
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-light rounded-pill px-3">Déconnexion</button>
                        </form>
                    @endguest
                </div>
            </div>
        </div>
    </nav>

    <!-- Messages Flash -->
    <main class="flex-grow-1">
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                    <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Pied de page Public -->
    <footer class="bg-azalai-dark text-white-50 py-4 mt-5 border-top border-secondary">
        <div class="container d-flex flex-column flex-md-row justify-content-between align-items-center small">
            <div>© {{ date('Y') }} Groupe Hôtels & Resorts. Afrique de l'Ouest.</div>
            <div class="mt-2 mt-md-0">
                <a href="{{ route('login') }}" class="text-azalai-gold text-decoration-none">
                    <i class="fa-solid fa-lock me-1"></i>Portail Staff & ERP
                </a>
            </div>
        </div>
    </footer>
</body>

</html>
