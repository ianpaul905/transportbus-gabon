@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="card" style="max-width:440px;margin:2rem auto">
    <h1 style="text-align:center">Connexion</h1>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-2">
            <label for="email">Adresse e-mail</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus>
        </div>
        <div class="mb-2">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" required>
        </div>
        <button type="submit" class="btn btn-block btn-lg mb-1">Se connecter</button>
        <p class="muted texte-centre">
            Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a>
        </p>
    </form>
</div>
@endsection