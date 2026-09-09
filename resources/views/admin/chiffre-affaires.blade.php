@extends('layouts.app')

@section('title', 'Chiffre d\'affaires')

@section('content')
<h1>Chiffre d'affaires</h1>

<div class="stats-grid">
    <div class="stat"><div class="valeur">{{ number_format($total, 0, ',', ' ') }} F</div><div class="libelle">Total encaissé (paiements validés)</div></div>
    @foreach($totalParMethode as $mode => $somme)
        <div class="stat">
            <div class="valeur">{{ number_format($somme, 0, ',', ' ') }} F</div>
            <div class="libelle">{{ \App\Services\MobileMoneyService::MODES[$mode] ?? $mode }}</div>
        </div>
    @endforeach
</div>

<div class="card">
    <h2>Historique des paiements</h2>
    @if($paiements->isEmpty())
        <p class="muted">Aucun paiement enregistré.</p>
    @else
        <table>
            <thead>
                <tr><th>Date</th><th>Réf. transaction</th><th>Mode</th><th>Montant</th><th>Client</th><th>Statut</th></tr>
            </thead>
            <tbody>
            @foreach($paiements as $p)
                <tr>
                    <td>{{ $p->created_at->format('d/m/Y H\hi') }}</td>
                    <td>{{ $p->reference_transaction }}<br><span class="muted" style="font-size:0.78rem">{{ $p->provider_reference ?? '' }}</span></td>
                    <td>{{ \App\Services\MobileMoneyService::MODES[$p->mode_paiement] ?? $p->mode_paiement }}</td>
                    <td>{{ number_format($p->montant, 0, ',', ' ') }} F</td>
                    <td>{{ $p->reservation->client_nom ?? '—' }}</td>
                    <td>
                        @if($p->statut === 'valide') <span class="badge badge-vert">Validé</span>
                        @else <span class="badge badge-jaune">En attente</span>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination">{{ $paiements->links() }}</div>
    @endif
</div>
@endsection