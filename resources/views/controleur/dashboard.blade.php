@extends('layouts.app')

@section('title', 'Espace contrôleur')

@section('content')
<h1>Espace contrôleur — {{ $departs_du_jour->count() }} départ(s) aujourd'hui</h1>

<div class="card mb-2 flex" style="justify-content:space-between">
    <p class="muted" style="margin-bottom:0">Vérifiez l'authenticité des billets et validez l'embarquement.</p>
    <a href="{{ route('controleur.scanner') }}" class="btn btn-lg">Scanner un e-billet</a>
</div>

@foreach($departs_du_jour as $trip)
    <div class="trip-card">
        <div>
            <div class="route">{{ $trip->ville_depart }} → {{ $trip->ville_arrivee }}</div>
            <div class="meta">
                {{ \Carbon\Carbon::parse($trip->heure_depart)->format('H\hi') }} • Bus {{ $trip->bus->immatriculation }}
            </div>
        </div>
        <div style="text-align:right">
            <div>
                <span class="badge badge-vert">{{ $trip->reservations->where('statut','confirmee')->count() }} confirmée(s)</span>
                <span class="badge badge-bleu">{{ $trip->reservations->where('statut','utilisee')->count() }} embarquée(s)</span>
            </div>
            <div class="muted mt-1">{{ $trip->bus->nombre_places }} places</div>
        </div>
    </div>
@endforeach

@if($departs_du_jour->isEmpty())
    <div class="card icone-vide">
        <p>Aucun départ programmé pour aujourd'hui.</p>
    </div>
@endif
@endsection