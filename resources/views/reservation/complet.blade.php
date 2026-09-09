@extends('layouts.app')

@section('title', 'Bus complet')

@section('content')
<div class="card">
    <h1>Bus complet</h1>
    <div class="alert alert-info">{{ $message }}</div>

    <div class="trip-card" style="background:#f9fafb">
        <div>
            <div class="route">{{ $trip->ville_depart }} → {{ $trip->ville_arrivee }}</div>
            <div class="meta">{{ \Carbon\Carbon::parse($trip->date_depart)->translatedFormat('l d/m/Y') }} à {{ \Carbon\Carbon::parse($trip->heure_depart)->format('H\hi') }}</div>
        </div>
        <span class="badge badge-rouge">Complet — {{ $trip->placesRestantes() }} place(s)</span>
    </div>

    @if($alternatives->isNotEmpty())
        <h2 class="mt-2">Prochains départs disponibles sur le même axe</h2>
        @foreach($alternatives as $alt)
            <div class="trip-card">
                <div>
                    <div class="route">{{ $alt->ville_depart }} → {{ $alt->ville_arrivee }}</div>
                    <div class="meta">
                        {{ \Carbon\Carbon::parse($alt->date_depart)->translatedFormat('l d/m/Y') }} à {{ \Carbon\Carbon::parse($alt->heure_depart)->format('H\hi') }}
                        • <span class="badge badge-vert">{{ $alt->placesRestantes() }} place(s) restante(s)</span>
                    </div>
                </div>
                <div class="flex">
                    <span class="prix">{{ number_format($alt->prix, 0, ',', ' ') }} FCFA</span>
                    <a href="{{ route('reservation.create', ['trip_id' => $alt->id]) }}" class="btn">Réserver</a>
                </div>
            </div>
        @endforeach
    @else
        <div class="card icone-vide mt-2">
            <p>Aucun autre départ disponible cette semaine. Revenez plus tard.</p>
        </div>
    @endif

    <a href="{{ route('home') }}" class="btn btn-secondary mt-2">Effectuer une autre recherche</a>
</div>
@endsection