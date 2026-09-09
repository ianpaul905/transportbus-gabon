@extends('layouts.app')

@section('title', 'E-billet')

@section('content')
<div class="card ebillet">
    <h1 class="texte-centre">Votre e-billet</h1>
    <div class="texte-centre mb-2">
        <div class="qr-box">
            {!! $qr->generateSvg($reservation->qr_code, 180) !!}
        </div>
    </div>

    <div class="texte-centre mb-2">
        <strong>{{ $reservation->client_nom }}</strong><br>
        <span class="muted">{{ $reservation->reference }}</span>
    </div>

    <div class="card" style="background:#f9fafb;padding:1rem">
        <div class="flex-between">
            <div>
                <div class="muted" style="font-size:0.8rem">Départ</div>
                <strong>{{ $reservation->trip->ville_depart }}</strong>
            </div>
            <div style="font-size:1.3rem">→</div>
            <div style="text-align:right">
                <div class="muted" style="font-size:0.8rem">Arrivée</div>
                <strong>{{ $reservation->trip->ville_arrivee }}</strong>
            </div>
        </div>
        <hr style="border:none;border-top:1px dashed var(--bordure);margin:0.9rem 0">
        <div class="flex-between">
            <div>
                <div class="muted" style="font-size:0.8rem">Date & heure</div>
                <strong>{{ \Carbon\Carbon::parse($reservation->trip->date_depart)->format('d/m/Y') }} — {{ \Carbon\Carbon::parse($reservation->trip->heure_depart)->format('H\hi') }}</strong>
            </div>
            <div>
                <div class="muted" style="font-size:0.8rem">Places</div>
                <strong>{{ $reservation->nombre_places }}</strong>
            </div>
        </div>
        <div class="flex-between mt-2">
            <div>
                <div class="muted" style="font-size:0.8rem">Montant payé</div>
                <strong>{{ number_format($reservation->montantTotal(), 0, ',', ' ') }} FCFA</strong>
            </div>
            <div>
                <div class="muted" style="font-size:0.8rem">Bus</div>
                <strong>{{ $reservation->trip->bus->immatriculation }}</strong>
            </div>
        </div>
    </div>

    <div class="mt-2" style="display:flex;gap:0.6rem;justify-content:center;flex-wrap:wrap">
        <button class="btn btn-jaune" style="color:#000" onclick="window.print()">Imprimer</button>
        <a href="{{ route('home') }}" class="btn btn-secondary">Retour à l'accueil</a>
    </div>
</div>
@endsection