@extends('layouts.app')

@section('title', 'Résultats de recherche')

@section('content')
<div class="flex-between mb-2">
    <h1 style="margin-bottom:0">Trajets disponibles</h1>
    <a href="{{ route('home') }}" class="btn btn-secondary">Nouvelle recherche</a>
</div>

<p class="mb-2 muted">
    Trajets de {{ request('ville_depart') }} vers {{ request('ville_arrivee') }}
    à partir du {{ \Carbon\Carbon::parse(request('date_depart'))->format('d/m/Y') }}
</p>

@if($trips->isEmpty())
    <div class="card icone-vide">
        <p>— Aucun trajet trouvé pour ces critères. —</p>
        <p class="muted" style="margin-top:0.5rem">Essayez avec une autre date ou une autre destination.</p>
    </div>
@else
    @foreach($trips as $trip)
        <div class="trip-card">
            <div>
                <div class="route">{{ $trip->ville_depart }} → {{ $trip->ville_arrivee }}</div>
                <div class="meta">
                    {{ \Carbon\Carbon::parse($trip->date_depart)->translatedFormat('l d/m/Y') }} à {{ \Carbon\Carbon::parse($trip->heure_depart)->format('H\hi') }}
                    • Bus {{ $trip->bus->immatriculation }} ({{ $trip->bus->nombre_places }} places)
                    @if($trip->bus->classe) • {{ $trip->bus->classe }} @endif
                </div>
            </div>
            <div class="flex" style="flex-wrap:wrap">
                <div style="text-align:right">
                    <div class="prix">{{ number_format($trip->prix, 0, ',', ' ') }} FCFA</div>
                    <div class="meta">
                        @if($trip->placesRestantes() > 0)
                            <span class="badge badge-vert">{{ $trip->placesRestantes() }} place(s) restante(s)</span>
                        @else
                            <span class="badge badge-rouge">Complet</span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('reservation.create', ['trip_id' => $trip->id]) }}" class="btn">
                    @if($trip->estComplet()) Voir alternatives @else Réserver @endif
                </a>
            </div>
        </div>
    @endforeach
@endif
@endsection