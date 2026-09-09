@extends('layouts.app')

@section('title', 'Réservation confirmée')

@section('content')
<div class="card texte-centre">
    <div class="alert alert-success">Paiement confirmé ! Votre réservation est maintenant <strong>confirmée</strong>.</div>

    <h1>Réservation confirmée 🎉</h1>
    <p class="muted">Votre e-billet a été généré. Présentez le QR Code au contrôleur le jour du départ.</p>

    <div class="qr-box mt-2">
        {!! $qr->generateSvg($reservation->qr_code, 220) !!}
    </div>

    <div class="card mt-2" style="max-width:640px;margin:1.5rem auto 0;text-align:left">
        <p><strong>Référence :</strong> {{ $reservation->reference }}</p>
        <p><strong>Passager :</strong> {{ $reservation->client_nom }}</p>
        <p><strong>Trajet :</strong> {{ $reservation->trip->ville_depart }} → {{ $reservation->trip->ville_arrivee }}</p>
        <p><strong>Départ :</strong> {{ \Carbon\Carbon::parse($reservation->trip->date_depart)->translatedFormat('l d/m/Y') }} à {{ \Carbon\Carbon::parse($reservation->trip->heure_depart)->format('H\hi') }}</p>
        <p><strong>Places :</strong> {{ $reservation->nombre_places }}</p>
        <p><strong>Montant payé :</strong> {{ number_format($reservation->montantTotal(), 0, ',', ' ') }} FCFA
            ({{ \App\Services\MobileMoneyService::MODES[$reservation->payment->mode_paiement] ?? $reservation->payment->mode_paiement }})</p>
        <p><strong>Référence transaction :</strong> {{ $reservation->payment->reference_transaction }}</p>
    </div>

    <div class="mt-3 flex" style="justify-content:center;flex-wrap:wrap">
        <a href="{{ route('payment.ebillet', $reservation->id) }}" class="btn btn-lg">Voir / télécharger l'e-billet</a>
        <a href="{{ route('home') }}" class="btn btn-secondary btn-lg">Retour à l'accueil</a>
    </div>
</div>
@endsection