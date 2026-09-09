@extends('layouts.app')

@section('title', 'Réservation')

@section('content')
<div class="card">
    <h1>Confirmer votre réservation</h1>
    <div class="trip-card" style="background:#f9fafb">
        <div>
            <div class="route">{{ $trip->ville_depart }} → {{ $trip->ville_arrivee }}</div>
            <div class="meta">
                {{ \Carbon\Carbon::parse($trip->date_depart)->translatedFormat('l d/m/Y') }} à {{ \Carbon\Carbon::parse($trip->heure_depart)->format('H\hi') }}
            </div>
        </div>
        <div class="prix">{{ number_format($trip->prix, 0, ',', ' ') }} FCFA / place</div>
    </div>

    <form method="POST" action="{{ route('reservation.store') }}">
        @csrf
        <input type="hidden" name="trip_id" value="{{ $trip->id }}">
        <div class="form-grid">
            <div>
                <label for="client_nom">Nom complet</label>
                <input type="text" name="client_nom" id="client_nom"
                       value="{{ old('client_nom', auth()->user()->name ?? '') }}" required>
            </div>
            <div>
                <label for="client_telephone">Numéro Mobile Money</label>
                <input type="text" name="client_telephone" id="client_telephone"
                       value="{{ old('client_telephone', auth()->user()->telephone ?? '') }}"
                       placeholder="+241 07 00 00 00" required>
            </div>
            <div>
                <label for="nombre_places">Nombre de places</label>
                <input type="number" name="nombre_places" id="nombre_places" min="1"
                       max="{{ min(10, $trip->placesRestantes()) }}" value="1" required>
            </div>
        </div>
        <p class="muted mt-2" id="total" data-prix="{{ $trip->prix }}">Total : <strong>{{ number_format($trip->prix, 0, ',', ' ') }} FCFA</strong></p>
        <button type="submit" class="btn btn-lg mt-2">Continuer vers le paiement</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('nombre_places');
    const total = document.getElementById('total');
    input.addEventListener('input', function () {
        const prix = parseFloat(total.dataset.prix);
        const qte = parseInt(input.value) || 1;
        total.innerHTML = 'Total : <strong>' + (prix * qte).toLocaleString('fr-FR') + ' FCFA</strong>';
    });
});
</script>
@endsection