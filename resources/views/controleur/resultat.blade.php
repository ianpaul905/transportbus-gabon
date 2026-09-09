@extends('layouts.app')

@section('title', 'Résultat du contrôle')

@section('content')
@if($reservation->statut === 'confirmee' || $reservation->statut === 'utilisee')
    <div class="alert alert-success">Billet valide pour le voyage {{ $reservation->trip->ville_depart }} → {{ $reservation->trip->ville_arrivee }}.</div>
@else
    <div class="alert alert-error">Billet {{ $reservation->statut }} — non valide pour l'embarquement.</div>
@endif

<div class="card" style="max-width:560px;margin:0 auto">
    <h1 style="text-align:center">Billet : {{ $reservation->reference }}</h1>
    <div style="text-align:center">
        <div class="qr-box">
            {!! (new \App\Services\QrCodeService())->generateSvg($reservation->qr_code, 140) !!}
        </div>
    </div>
    <div class="card mt-2" style="background:#f9fafb">
        <p><strong>Passager :</strong> {{ $reservation->client_nom }}</p>
        <p><strong>Téléphone :</strong> {{ $reservation->client_telephone }}</p>
        <p><strong>Trajet :</strong> {{ $reservation->trip->ville_depart }} → {{ $reservation->trip->ville_arrivee }}</p>
        <p><strong>Départ :</strong> {{ \Carbon\Carbon::parse($reservation->trip->date_depart)->format('d/m/Y') }} à {{ \Carbon\Carbon::parse($reservation->trip->heure_depart)->format('H\hi') }}</p>
        <p><strong>Bus :</strong> {{ $reservation->trip->bus->immatriculation }}</p>
        <p><strong>Places :</strong> {{ $reservation->nombre_places }}</p>
        <p><strong>Statut :</strong>
            @switch($reservation->statut)
                @case('confirmee') <span class="badge badge-vert">Confirmée — embarquer</span> @break
                @case('utilisee') <span class="badge badge-bleu">Déjà embarqué</span> @break
                @default <span class="badge badge-rouge">{{ ucfirst($reservation->statut) }}</span>
            @endswitch
        </p>
    </div>

    @if($reservation->statut === 'confirmee')
        <form method="POST" action="{{ route('controleur.embarquer', $reservation->id) }}" class="mt-2">
            @csrf
            <button type="submit" class="btn btn-lg btn-jaune btn-block" style="color:#000"
                    onclick="return confirm('Valider l\'embarquement de {{ $reservation->client_nom }} ?');">
                ✅ Valider l'embarquement
            </button>
        </form>
    @endif

    <div class="flex mt-2" style="justify-content:center">
        <a href="{{ route('controleur.scanner') }}" class="btn btn-secondary btn-block">Scanner un autre billet</a>
    </div>
</div>
@endsection