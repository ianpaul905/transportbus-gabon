@extends('layouts.app')

@section('title', 'Gestion des bus')

@section('content')
<h1>Gestion des bus</h1>

<div class="card mb-2">
    <h2>Ajouter un bus</h2>
    <form method="POST" action="{{ route('admin.bus.store') }}">
        @csrf
        <div class="form-grid">
            <div>
                <label for="immatriculation">Immatriculation</label>
                <input type="text" name="immatriculation" id="immatriculation" required>
            </div>
            <div>
                <label for="nombre_places">Nombre de places</label>
                <input type="number" name="nombre_places" id="nombre_places" min="1" max="100" required>
            </div>
            <div>
                <label for="classe">Classe</label>
                <select name="classe" id="classe">
                    <option value="">Standard</option>
                    <option value="VIP">VIP</option>
                    <option value="Première">Première</option>
                </select>
            </div>
            <div>
                <label for="agency_id">Agence</label>
                <select name="agency_id" id="agency_id">
                    <option value="">— Aucune —</option>
                    @foreach($agences as $a)
                        <option value="{{ $a->id }}">{{ $a->nom }} — {{ $a->ville }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <button type="submit" class="btn mt-2">Ajouter le bus</button>
    </form>
</div>

<div class="card">
    <h2>Bus de la flotte</h2>
    @if($bus->isEmpty())
        <p class="muted">Aucun bus enregistré.</p>
    @else
        <table>
            <thead>
                <tr><th>Immatriculation</th><th>Places</th><th>Classe</th><th>Agence</th><th>Statut</th><th>Act.</th></tr>
            </thead>
            <tbody>
            @foreach($bus as $b)
                <tr>
                    <td><strong>{{ $b->immatriculation }}</strong></td>
                    <td>{{ $b->nombre_places }}</td>
                    <td>{{ $b->classe ?? 'Standard' }}</td>
                    <td>{{ $b->agency->nom ?? '—' }}</td>
                    <td>
                        @if($b->statut === 'actif') <span class="badge badge-vert">Actif</span>
                        @elseif($b->statut === 'en_maintenance') <span class="badge badge-jaune">En maintenance</span>
                        @else <span class="badge badge-rouge">Hors service</span>
                        @endif
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.bus.update', $b->id) }}">
                            @csrf
                            @method('PATCH')
                            <select name="statut" onchange="this.form.submit()">
                                <option value="actif" @selected($b->statut === 'actif')>Actif</option>
                                <option value="en_maintenance" @selected($b->statut === 'en_maintenance')>Maintenance</option>
                                <option value="hors_service" @selected($b->statut === 'hors_service')>Hors service</option>
                            </select>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination">{{ $bus->links() }}</div>
    @endif
</div>
@endsection