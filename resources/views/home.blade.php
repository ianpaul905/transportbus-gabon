@extends('layouts.app')

@section('title', 'Accueil — TransportBus Gabon')

@section('content')
<div class="hero">
    <h1>Voyagez sans file d'attente</h1>
    <p>Réservez votre billet de bus interurbain et payez par Mobile Money (Airtel Money / Moov Money) depuis votre téléphone.</p>
</div>

<div class="card">
    <h2>Rechercher un trajet</h2>
    <form method="POST" action="{{ route('recherche') }}">
        @csrf
        <div class="form-grid">
            <div>
                <label for="ville_depart">Ville de départ</label>
                <select name="ville_depart" id="ville_depart" required>
                    <option value="">— Choisir —</option>
                    @foreach($villes as $ville)
                        <option value="{{ $ville }}" @selected(old('ville_depart', request('ville_depart')) == $ville)>{{ $ville }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="ville_arrivee">Ville d'arrivée</label>
                <select name="ville_arrivee" id="ville_arrivee" required>
                    <option value="">— Choisir —</option>
                    @foreach($villes as $ville)
                        <option value="{{ $ville }}" @selected(old('ville_arrivee', request('ville_arrivee')) == $ville)>{{ $ville }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_depart">Date de départ</label>
                <input type="date" name="date_depart" id="date_depart"
                       value="{{ old('date_depart', request('date_depart')) }}"
                       min="{{ now()->toDateString() }}" required>
            </div>
            <div style="display:flex;align-items:flex-end">
                <button type="submit" class="btn btn-lg btn-block">Rechercher les trajets</button>
            </div>
        </div>
    </form>
</div>

<div class="card mt-2">
    <h2>Comment ça marche ?</h2>
    <div class="split">
        <div>
            <p><strong>1.</strong> Recherchez un trajet (Libreville → Mouila, Oyem, Franceville…).</p>
            <p><strong>2.</strong> Consultez les places disponibles et le prix.</p>
            <p><strong>3.</strong> Réservez et payez via Airtel Money ou Moov Money.</p>
        </div>
        <div>
            <p><strong>4.</strong> Recevez instantanément votre e-billet avec QR Code.</p>
            <p><strong>5.</strong> Présentez le QR Code au contrôleur à l'embarquement.</p>
            <p><strong>6.</strong> Le bus est complet ? Le système vous propose automatiquement les prochaines dates disponibles.</p>
        </div>
    </div>
</div>
@endsection