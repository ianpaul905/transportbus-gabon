@extends('layouts.app')

@section('title', 'Paiement Mobile Money')

@section('content')
<div class="split">
    <div class="card">
        <h1>Paiement</h1>

        <div class="trip-card" style="background:#f9fafb">
            <div>
                <div class="route">{{ $reservation->trip->ville_depart }} → {{ $reservation->trip->ville_arrivee }}</div>
                <div class="meta">
                    {{ \Carbon\Carbon::parse($reservation->trip->date_depart)->translatedFormat('l d/m/Y') }} à {{ \Carbon\Carbon::parse($reservation->trip->heure_depart)->format('H\hi') }}
                    <br>Référence : <strong>{{ $reservation->reference }}</strong>
                    <br>{{ $reservation->client_nom }} — {{ $reservation->nombre_places }} place(s)
                </div>
            </div>
            <div class="prix">{{ number_format($reservation->montantTotal(), 0, ',', ' ') }} FCFA</div>
        </div>

        <h2>Choisissez votre opérateur</h2>
        <form method="POST" action="{{ route('payment.initier', $reservation->id) }}">
            @csrf
            <div class="form-grid">
                <div>
                    <label for="telephone">N° Mobile Money</label>
                    <input type="text" name="telephone" id="telephone"
                           value="{{ old('telephone', $reservation->client_telephone) }}" required>
                </div>
                <div>
                    <label for="mode_paiement">Opérateur</label>
                    <select name="mode_paiement" id="mode_paiement" required>
                        <option value="">— Choisir —</option>
                        @foreach($modes as $valeur => $libelle)
                            <option value="{{ $valeur }}" @selected(old('mode_paiement') == $valeur)>{{ $libelle }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-lg btn-jaune mt-2" style="color:#000">Initier le paiement</button>
        </form>

        <p class="muted mt-2">
            Vous recevrez une demande de paiement sur votre téléphone. Validez-la pour confirmer la réservation.
        </p>
    </div>

    <div class="card">
        <h2>Pourquoi le Mobile Money ?</h2>
        <p class="muted">Le paiement Mobile Money (Airtel Money & Moov Money) est le moyen de paiement le plus répandu au Gabon, accessible même sans compte bancaire.</p>
        <ul style="margin-left:1.2rem;line-height:1.9">
            <li>Paiement instantané depuis votre téléphone</li>
            <li>Pas de déplacement à l'agence</li>
            <li>Confirmation automatique de la réservation</li>
            <li>E-billet sécurisé avec QR Code à l'issue du paiement</li>
        </ul>
    </div>
</div>
@endsection