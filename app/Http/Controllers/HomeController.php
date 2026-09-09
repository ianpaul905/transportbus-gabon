<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $villes = collect([
            'Libreville',
            'Lambaréné',
            'Mouila',
            'Oyem',
            'Franceville',
            'Port-Gentil',
            'Kango',
            'Ntoum',
        ]);

        return view('home', compact('villes'));
    }

    public function rechercher(Request $request)
    {
        $request->validate([
            'ville_depart' => 'required|string',
            'ville_arrivee' => 'required|string|different:ville_depart',
            'date_depart' => 'required|date',
        ]);

        $trips = Trip::query()
            ->where('ville_depart', $request->ville_depart)
            ->where('ville_arrivee', $request->ville_arrivee)
            ->where('date_depart', '>=', $request->date_depart)
            ->whereHas('bus', fn ($q) => $q->where('statut', 'actif'))
            ->orderBy('date_depart')
            ->orderBy('heure_depart')
            ->with('bus')
            ->get();

        $villes = collect([
            'Libreville',
            'Lambaréné',
            'Mouila',
            'Oyem',
            'Franceville',
            'Port-Gentil',
            'Kango',
            'Ntoum',
        ]);

        return view('search-results', compact('trips', 'villes'))
            ->with('ancien', $request->all());
    }
}