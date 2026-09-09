@extends('layouts.app')

@section('title', 'Tableau de bord administrateur')

@section('content')
<div class="flex-between mb-2">
    <h1 style="margin-bottom:0">Tableau de bord administrateur</h1>
    <div class="flex">
        <a href="{{ route('admin.trajets') }}" class="btn">Programmer un trajet</a>
        <a href="{{ route('admin.bus') }}" class="btn btn-secondary">Gérer les bus</a>
        <a href="{{ route('admin.chiffre-affaires') }}" class="btn btn-jaune" style="color:#000">Chiffre d'affaires</a>
    </div>
</div>

<div class="stats-grid">
    <div class="stat"><div class="valeur">{{ $stats['trajets'] }}</div><div class="libelle">Trajets programmés</div></div>
    <div class="stat"><div class="valeur">{{ $stats['bus'] }}</div><div class="libelle">Bus actifs</div></div>
    <div class="stat"><div class="valeur">{{ $stats['reservations_confirmees'] }}</div><div class="libelle">Réservations confirmées</div></div>
    <div class="stat"><div class="valeur">{{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }} F</div><div class="libelle">Chiffre d'affaires</div></div>
</div>

<div class="split">
    <div class="card">
        <h2>Prochains départs</h2>
        @if($prochains_departs->isEmpty())
            <p class="muted">Aucun départ programmé.</p>
        @else
            <table>
                <thead><tr><th>Trajet</th><th>Date</th><th>Heure</th><th>Bus</th><th>Places</th></tr></thead>
                <tbody>
                @foreach($prochains_departs as $t)
                    <tr>
                        <td>{{ $t->ville_depart }} → {{ $t->ville_arrivee }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->date_depart)->format('d/m/Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($t->heure_depart)->format('H\hi') }}</td>
                        <td>{{ $t->bus->immatriculation }}</td>
                        <td>
                            @if($t->placesRestantes() > 0)
                                <span class="badge badge-vert">{{ $t->placesRestantes() }}</span>
                            @else
                                <span class="badge badge-rouge">Complet</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>

    <div class="card">
        <h2>Dernières réservations</h2>
        @if($dernieres_reservations->isEmpty())
            <p class="muted">Aucune réservation pour le moment.</p>
        @else
            <table>
                <thead><tr><th>Réf.</th><th>Client</th><th>Trajet</th><th>Statut</th></tr></thead>
                <tbody>
                @foreach($dernieres_reservations as $r)
                    <tr>
                        <td>{{ $r->reference }}</td>
                        <td>{{ $r->client_nom }}</td>
                        <td>{{ $r->trip->ville_depart }} → {{ $r->trip->ville_arrivee }}</td>
                        <td>
                            @switch($r->statut)
                                @case('confirmee') <span class="badge badge-vert">Confirmée</span> @break
                                @case('utilisee') <span class="badge badge-bleu">Utilisée</span> @break
                                @case('en_attente') <span class="badge badge-jaune">En attente</span> @break
                                @default <span class="badge badge-rouge">Annulée</span>
                            @endswitch
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection