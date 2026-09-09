@extends('layouts.app')

@section('title', 'Gestion des trajets')

@section('content')
<h1>Gestion des trajets</h1>

<div class="card mb-2">
    <h2>Programmer un nouveau trajet</h2>
    <form method="POST" action="{{ route('admin.trajets.store') }}">
        @csrf
        <div class="form-grid">
            <div>
                <label for="ville_depart">Ville de départ</label>
                <select name="ville_depart" id="ville_depart" required>
                    <option value="">— Choisir —</option>
                    @foreach($villes as $ville)
                        <option value="{{ $ville }}" @selected(old('ville_depart') == $ville)>{{ $ville }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="ville_arrivee">Ville d'arrivée</label>
                <select name="ville_arrivee" id="ville_arrivee" required>
                    <option value="">— Choisir —</option>
                    @foreach($villes as $ville)
                        <option value="{{ $ville }}" @selected(old('ville_arrivee') == $ville)>{{ $ville }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="date_depart">Date</label>
                <input type="date" name="date_depart" id="date_depart" required>
            </div>
            <div>
                <label for="heure_depart">Heure</label>
                <input type="time" name="heure_depart" id="heure_depart" required>
            </div>
            <div>
                <label for="prix">Prix (FCFA)</label>
                <input type="number" name="prix" id="prix" min="0" step="100" required>
            </div>
            <div>
                <label for="bus_id">Bus</label>
                <select name="bus_id" id="bus_id" required>
                    <option value="">— Choisir —</option>
                    @foreach($bus as $b)
                        <option value="{{ $b->id }}">{{ $b->immatriculation }} ({{ $b->nombre_places }} pl., {{ $b->classe ?? 'standard' }})</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="btn mt-2">Enregistrer le trajet</button>
    </form>
</div>

<div class="card">
    <h2>Trajets existants</h2>
    @if($trajets->isEmpty())
        <p class="muted">Aucun trajet enregistré.</p>
    @else
        <table>
            <thead>
                <tr><th>Trajet</th><th>Date</th><th>Heure</th><th>Prix</th><th>Bus</th><th>Act.</th></tr>
            </thead>
            <tbody>
            @foreach($trajets as $t)
                <tr>
                    <td>{{ $t->ville_depart }} → {{ $t->ville_arrivee }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->date_depart)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->heure_depart)->format('H\hi') }}</td>
                    <td>{{ number_format($t->prix, 0, ',', ' ') }} F</td>
                    <td>{{ $t->bus->immatriculation }} <span class="muted">({{ $t->placesRestantes() }}/{{ $t->bus->nombre_places }})</span></td>
                    <td>
                        <form method="POST" action="{{ route('admin.trajets.destroy', $t->id) }}"
                              onsubmit="return confirm('Supprimer ce trajet ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-rouge" style="padding:0.3rem 0.7rem">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination">{{ $trajets->links() }}</div>
    @endif
</div>
@endsection