@extends('layouts.app')

@section('title', 'Réservations')

@section('content')
<h1>Réservations</h1>

<div class="card">
    @if($reservations->isEmpty())
        <p class="muted">Aucune réservation enregistrée.</p>
    @else
        <table>
            <thead>
                <tr><th>Référence</th><th>Client</th><th>Téléphone</th><th>Trajet</th><th>Places</th><th>Paiement</th><th>Statut</th></tr>
            </thead>
            <tbody>
            @foreach($reservations as $r)
                <tr>
                    <td>{{ $r->reference }}</td>
                    <td>{{ $r->client_nom }}</td>
                    <td>{{ $r->client_telephone }}</td>
                    <td>{{ $r->trip->ville_depart }} → {{ $r->trip->ville_arrivee }}<br>
                        <span class="muted">{{ \Carbon\Carbon::parse($r->trip->date_depart)->format('d/m/Y') }} {{ \Carbon\Carbon::parse($r->trip->heure_depart)->format('H\hi') }}</span></td>
                    <td>{{ $r->nombre_places }}</td>
                    <td>
                        @if($r->payment)
                            <span class="badge @if($r->payment->statut === 'valide') badge-vert @else badge-jaune @endif">
                                {{ \App\Services\MobileMoneyService::MODES[$r->payment->mode_paiement] ?? $r->payment->mode_paiement }}
                            </span>
                        @else
                            <span class="muted">—</span>
                        @endif
                    </td>
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
        <div class="pagination">{{ $reservations->links() }}</div>
    @endif
</div>
@endsection