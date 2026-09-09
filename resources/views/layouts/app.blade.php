<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'TransportBus Gabon') — Réservation de billets</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --vert: #0b7a3b;
            --vert-fonce: #065c2c;
            --jaune: #f5c518;
            --fond: #f4f6f5;
            --texte: #1f2937;
            --gris: #6b7280;
            --bordure: #e5e7eb;
        }
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            background: var(--fond);
            color: var(--texte);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        header {
            background: linear-gradient(135deg, var(--vert) 0%, var(--vert-fonce) 100%);
            color: #fff;
            padding: 0.9rem 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        header .container {
            max-width: 1150px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            color: #fff;
            font-weight: 700;
            font-size: 1.15rem;
        }
        .logo-badge {
            background: var(--jaune);
            color: var(--vert-fonce);
            border-radius: 8px;
            padding: 0.25rem 0.5rem;
            font-size: 0.85rem;
        }
        nav { display: flex; align-items: center; gap: 0.4rem; flex-wrap: wrap; }
        nav a {
            color: #eafff1;
            text-decoration: none;
            padding: 0.45rem 0.8rem;
            border-radius: 6px;
            font-size: 0.92rem;
            transition: background 0.2s;
        }
        nav a:hover { background: rgba(255,255,255,0.15); }
        nav .btn-inverse {
            background: var(--jaune);
            color: var(--vert-fonce);
            font-weight: 600;
        }
        nav .btn-inverse:hover { filter: brightness(1.05); }
        .user-badge {
            font-size: 0.9rem;
            padding: 0.35rem 0.7rem;
            background: rgba(255,255,255,0.12);
            border-radius: 6px;
        }
        main { flex: 1; width: 100%; max-width: 1150px; margin: 0 auto; padding: 1.5rem; }
        .card {
            background: #fff;
            border: 1px solid var(--bordure);
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 1px 4px rgba(0,0,0,0.04);
        }
        h1 { font-size: 1.6rem; margin-bottom: 0.5rem; }
        h2 { font-size: 1.25rem; margin-bottom: 1rem; }
        .hero {
            text-align: center;
            padding: 2.5rem 1.5rem;
            background: linear-gradient(135deg, rgba(11,122,59,0.08), rgba(245,197,24,0.1));
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }
        .hero h1 { font-size: 2rem; }
        .hero p { color: var(--gris); margin-top: 0.4rem; }
        .form-grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); }
        label { display: block; font-size: 0.85rem; font-weight: 600; margin-bottom: 0.3rem; color: #374151; }
        input, select, textarea {
            width: 100%;
            padding: 0.6rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 7px;
            font-size: 0.95rem;
            background: #fff;
            color: var(--texte);
        }
        input:focus, select:focus { outline: 2px solid var(--vert); border-color: var(--vert); }
        .btn {
            display: inline-block;
            padding: 0.6rem 1.3rem;
            border: none;
            border-radius: 7px;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: opacity 0.2s, transform 0.1s;
            background: var(--vert);
            color: #fff;
        }
        .btn:hover { opacity: 0.92; }
        .btn:active { transform: scale(0.98); }
        .btn-jaune { background: var(--jaune); color: var(--vert-fonce); }
        .btn-rouge { background: #dc2626; color: #fff; }
        .btn-gris { background: #6b7280; color: #fff; }
        .btn-secondary { background: #f3f4f6; color: var(--texte); border: 1px solid var(--bordure); }
        .btn-lg { padding: 0.8rem 1.8rem; font-size: 1.05rem; }
        .btn-block { width: 100%; }
        .error { color: #dc2626; font-size: 0.85rem; margin-top: 0.25rem; }
        .alert { padding: 0.85rem 1.1rem; border-radius: 8px; margin-bottom: 1rem; font-size: 0.93rem; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .alert-error, .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .alert-info { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
        table { width: 100%; border-collapse: collapse; font-size: 0.92rem; }
        th { text-align: left; padding: 0.65rem 0.8rem; background: #f9fafb; border-bottom: 2px solid var(--bordure); }
        td { padding: 0.65rem 0.8rem; border-bottom: 1px solid var(--bordure); }
        tr:hover td { background: #fafdfb; }
        .badge { display: inline-block; padding: 0.2rem 0.6rem; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-vert { background: #d1fae5; color: #065f46; }
        .badge-jaune { background: #fef3c7; color: #92400e; }
        .badge-rouge { background: #fee2e2; color: #991b1b; }
        .badge-gris { background: #e5e7eb; color: #374151; }
        .badge-bleu { background: #dbeafe; color: #1e40af; }
        .badge-vert { background: #d1fae5; color: #065f46; }
        .trip-card { border: 1px solid var(--bordure); border-radius: 10px; padding: 1rem 1.2rem; margin-bottom: 0.9rem; display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; background: #fff; }
        .trip-card .route { font-weight: 700; font-size: 1.05rem; }
        .trip-card .meta { color: var(--gris); font-size: 0.85rem; margin-top: 0.2rem; }
        .trip-card .prix { font-weight: 700; color: var(--vert); font-size: 1.1rem; }
        .stats-grid { display: grid; gap: 1rem; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); margin-bottom: 1.5rem; }
        .stat { background: #fff; border: 1px solid var(--bordure); border-radius: 10px; padding: 1.2rem; text-align: center; }
        .stat .valeur { font-size: 1.7rem; font-weight: 700; color: var(--vert); }
        .stat .libelle { color: var(--gris); font-size: 0.85rem; margin-top: 0.25rem; }
        .qr-box { background: #fff; border: 1px dashed var(--bordure); border-radius: 10px; padding: 1.2rem; display: inline-block; }
        .ebillet { max-width: 480px; margin: 0 auto; }
        .split { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; align-items: start; }
        @media (max-width: 700px) { .split { grid-template-columns: 1fr; } }
        footer { text-align: center; padding: 1rem; color: var(--gris); font-size: 0.85rem; background: #fff; border-top: 1px solid var(--bordure); }
        .muted { color: var(--gris); font-size: 0.9rem; }
        .mb-1 { margin-bottom: 0.5rem; } .mb-2 { margin-bottom: 1rem; } .mb-3 { margin-bottom: 1.5rem; }
        .mt-2 { margin-top: 1rem; } .mt-3 { margin-top: 1.5rem; }
        .flex { display: flex; gap: 0.6rem; align-items: center; }
        .flex-between { display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap; }
        .pagination { margin-top: 1rem; }
        .pagination nav div { display: flex; gap: 0.3rem; }
        .pagination a, .pagination span { padding: 0.35rem 0.7rem; border: 1px solid var(--bordure); border-radius: 6px; text-decoration: none; color: var(--texte); font-size: 0.9rem; }
        .pagination .active { background: var(--vert); color: #fff; border-color: var(--vert); }
        .texte-centre { text-align: center; }
        .icone-vide { text-align: center; color: var(--gris); padding: 2rem; }
    </style>
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