<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TransportBus Gabon') — Réservation de billets</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🚌</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<header>
    <div class="container">
        <a href="{{ route('home') }}" class="logo">
            <span class="logo-badge">Bus</span>
            TransportBus <span style="font-weight:400">Gabon</span>
        </a>
        <nav>
            <a href="{{ route('home') }}">Accueil</a>
            @auth
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}">Espace admin</a>
                @endif
                @if(auth()->user()->isControleur())
                    <a href="{{ route('controleur.dashboard') }}">Contrôle</a>
                    <a href="{{ route('controleur.scanner') }}">Scanner</a>
                @endif
                <span class="user-badge">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" style="display:inline">
                    @csrf
                    <button type="submit" class="btn btn-gris" style="padding:0.4rem 0.8rem">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}">Connexion</a>
                <a href="{{ route('register') }}" class="btn btn-inverse" style="padding:0.45rem 1rem">Créer un compte</a>
            @endauth
        </nav>
    </div>
</header>

<main>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-error">
            <ul style="margin-left:1rem">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>

<footer>
    TransportBus Gabon — Application de réservation de billets interurbains avec paiement Mobile Money.<br>
    Projet DUT Génie Logiciel — I.S.T Libreville © {{ date('Y') }}
</footer>
</body>
</html>