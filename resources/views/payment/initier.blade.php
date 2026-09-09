@extends('layouts.app')

@section('title', 'Demande de paiement envoyée')

@section('content')
<div class="card texte-centre" style="max-width:640px;margin:0 auto">
    <h1>📱 Demande de paiement envoyée</h1>
    <div class="alert alert-info" style="text-align:left">{{ $message }}</div>

    <div class="card mt-2" style="background:#f9fafb;text-align:left">
        <p><strong>Référence de transaction :</strong> {{ $payment->reference_transaction }}</p>
        <p><strong>Montant :</strong> {{ number_format($payment->montant, 0, ',', ' ') }} FCFA</p>
        <p><strong>Réservation :</strong> {{ $reservation->reference }} — {{ $reservation->trip->ville_depart }} → {{ $reservation->trip->ville_arrivee }}</p>
    </div>

    <p class="mb-2 muted">
        Une notification de paiement a été envoyée au numéro <strong>{{ $payment->telephone }}</strong>.
        Saisissez votre code PIN sur votre téléphone puis confirmez ci-dessous.
    </p>

    <form method="POST" action="{{ route('payment.confirmer', $reservation->id) }}">
        @csrf
        <button type="submit" class="btn btn-lg btn-jaune" style="color:#000">J'ai confirmé le paiement sur mon téléphone</button>
    </form>

    <p class="muted mt-2" style="font-size:0.8rem">Démo : dans ce prototype, la transaction est confirmée automatiquement.</p>
</div>
@endsection