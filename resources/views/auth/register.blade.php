@extends('layouts.app')

@section('title', 'Créer un compte')

@section('content')
<div class="card" style="max-width:440px;margin:2rem auto">
    <h1 style="text-align:center">Créer un compte</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-2">
            <label for="name">Nom complet</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus>
        </div>
        <div class="mb-2">
            <label for="email">Adresse e-mail</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>
        <div class="mb-2">
            <label for="telephone">Numéro Mobile Money</label>
            <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}" placeholder="+241 07 00 00 00" required>
        </div>
        <div class="mb-2">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div class="mb-2">
            <label for="password_confirmation">Confirmer le mot de passe</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required>
        </div>
        <button type="submit" class="btn btn-block btn-lg mb-1">Créer mon compte</button>
        <p class="muted texte-centre">
            Déjà inscrit ? <a href="{{ route('login') }}">Se connecter</a>
        </p>
    </form>
</div>
@endsection