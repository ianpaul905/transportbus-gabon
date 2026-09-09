@extends('layouts.app')

@section('title', 'Scanner un e-billet')

@section('content')
<div class="card" style="max-width:560px;margin:0 auto;text-align:center">
    <h1>Scanner un e-billet</h1>
    <p class="muted mb-3">Saisissez le numéro du billet ou scannez le QR Code. Utilisez votre caméra avec un lecteur QR, ou entrez manuellement la référence.</p>

    <form method="POST" action="{{ route('controleur.verifier') }}">
        @csrf
        <div class="mb-2">
            <label for="reference">Référence du billet ou contenu QR</label>
            <input type="text" name="reference" id="reference" placeholder="Ex : RES-XXXX ou le contenu du QR Code" required autofocus>
        </div>
        <button type="submit" class="btn btn-lg btn-block">Vérifier le billet</button>
    </form>

    <div class="card mt-2" style="background:#f9fafb">
        <p class="muted" style="margin-bottom:0">
            <strong>Astuce :</strong> l'e-billet du passager contient un QR Code avec sa référence.
            Ce prototype accepte la saisie manuelle de la référence (ex : <code>RES-4F9A2C1B</code>) ou du contenu JSON du QR code.
        </p>
    </div>
</div>
@endsection