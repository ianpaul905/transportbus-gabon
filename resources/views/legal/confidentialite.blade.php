@extends('layouts.app')

@section('title', 'Politique de confidentialité')

@section('content')
<div class="card">
    <h1>Politique de confidentialité</h1>

    <h2>Données collectées</h2>
    <p>
        Lors d'une réservation ou de la création d'un compte, les données suivantes
        sont enregistrées : nom, adresse e-mail, numéro de téléphone et détails de
        la réservation (trajet, date, nombre de places).
    </p>

    <h2>Finalité</h2>
    <p>
        Ces données servent uniquement au fonctionnement de la démonstration :
        identifier la réservation, générer l'e-billet et permettre au contrôleur de
        vérifier le billet.
    </p>

    <h2>Conservation et partage</h2>
    <p>
        Les données sont hébergées sur une base PostgreSQL gérée par Neon Inc. et ne
        sont ni vendues ni transmises à des tiers à des fins commerciales.
    </p>

    <h2>Sécurité</h2>
    <p>
        Les mots de passe sont stockés sous forme hachée. Le site est servi
        exclusivement en HTTPS et les cookies de session sont protégés.
    </p>

    <h2>Vos droits</h2>
    <p>
        Pour toute demande d'accès, de rectification ou de suppression de vos
        données, contactez l'éditeur via
        <a href="https://github.com/ianpaul905/transportbus-gabon/issues">GitHub</a>.
    </p>
</div>
@endsection
