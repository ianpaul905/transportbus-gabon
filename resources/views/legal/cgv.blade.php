@extends('layouts.app')

@section('title', 'Conditions générales')

@section('content')
<div class="card">
    <h1>Conditions générales de vente</h1>

    <h2>1. Objet</h2>
    <p>
        Ces conditions encadrent l'utilisation de TransportBus Gabon, application de
        démonstration permettant de rechercher des trajets de bus interurbains,
        d'effectuer une réservation et de générer un e-billet.
    </p>

    <h2>2. Caractère de démonstration</h2>
    <p>
        Le service est fourni à des fins pédagogiques. Les paiements Mobile Money
        sont simulés : aucun montant n'est prélevé et aucune réservation ne donne
        droit à un transport réel.
    </p>

    <h2>3. Réservation</h2>
    <p>
        La réservation est confirmée après l'étape de paiement simulé. Un e-billet
        comportant un QR Code est alors généré et peut être présenté au contrôleur
        dans le cadre de la démonstration.
    </p>

    <h2>4. Responsabilité</h2>
    <p>
        L'éditeur ne saurait être tenu responsable des dommages résultant de
        l'utilisation de cette démonstration ni de l'indisponibilité du service.
    </p>

    <h2>5. Données personnelles</h2>
    <p>
        Le traitement des données est décrit dans la
        <a href="{{ route('legal.confidentialite') }}">politique de confidentialité</a>.
    </p>
</div>
@endsection
