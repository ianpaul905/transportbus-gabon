@extends('layouts.app')

@section('title', 'Mentions légales')

@section('content')
<div class="card">
    <h1>Mentions légales</h1>

    <h2>Éditeur du site</h2>
    <p>
        TransportBus Gabon est un projet académique réalisé dans le cadre du
        DUT Génie Logiciel de l'I.S.T. Libreville (Gabon).
    </p>
    <p>
        Contact : <a href="https://github.com/ianpaul905/transportbus-gabon/issues">https://github.com/ianpaul905/transportbus-gabon/issues</a>
    </p>

    <h2>Hébergement</h2>
    <p>
        Le site est hébergé par Render Services, Inc. (San Francisco, États-Unis),
        avec une base de données PostgreSQL hébergée par Neon Inc.
    </p>

    <h2>Nature du service</h2>
    <p>
        Ce site est une démonstration. Les paiements Mobile Money (Airtel Money et
        Moov Money) y sont simulés : aucune somme réelle n'est débitée et aucun
        billet n'est émis par un transporteur réel.
    </p>

    <h2>Propriété intellectuelle</h2>
    <p>
        Le code source est publié sur
        <a href="https://github.com/ianpaul905/transportbus-gabon">GitHub</a>.
        Les marques et logos cités appartiennent à leurs propriétaires respectifs.
    </p>
</div>
@endsection
