<?php

namespace App\Http\Controllers;

use App\Models\Agency;
use App\Models\Bus;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\Trip;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'trajets' => Trip::count(),
            'bus' => Bus::where('statut', 'actif')->count(),
            'reservations_confirmees' => Reservation::where('statut', 'confirmee')->count(),
            'chiffre_affaires' => Payment::where('statut', 'valide')->sum('montant'),
        ];

        $prochains_departs = Trip::with('bus')
            ->where('date_depart', '>=', now()->toDateString())
            ->orderBy('date_depart')
            ->orderBy('heure_depart')
            ->limit(10)
            ->get();

        $dernieres_reservations = Reservation::with('trip')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'prochains_departs', 'dernieres_reservations'));
    }

    public function trajets()
    {
        $trajets = Trip::with('bus')->orderBy('date_depart', 'desc')->paginate(15);
        $bus = Bus::where('statut', 'actif')->get();
        $villes = ['Libreville', 'Lambaréné', 'Mouila', 'Oyem', 'Franceville', 'Port-Gentil', 'Kango', 'Ntoum'];

        return view('admin.trajets', compact('trajets', 'bus', 'villes'));
    }

    public function trajetStore(Request $request)
    {
        $request->validate([
            'ville_depart' => 'required|string',
            'ville_arrivee' => 'required|string|different:ville_depart',
            'date_depart' => 'required|date',
            'heure_depart' => 'required',
            'prix' => 'required|numeric|min:0',
            'bus_id' => 'required|exists:buses,id',
        ]);

        Trip::create($request->only([
            'ville_depart', 'ville_arrivee', 'date_depart', 'heure_depart', 'prix', 'bus_id'
        ]));

        return back()->with('success', 'Trajet programmé avec succès.');
    }

    public function trajetDestroy(Trip $trip)
    {
        $trip->delete();

        return back()->with('success', 'Trajet supprimé.');
    }

    public function bus()
    {
        $bus = Bus::with('agency')->paginate(15);
        $agences = Agency::all();

        return view('admin.bus', compact('bus', 'agences'));
    }

    public function busStore(Request $request)
    {
        $request->validate([
            'immatriculation' => 'required|string|unique:buses,immatriculation',
            'nombre_places' => 'required|integer|min:1|max:100',
            'classe' => 'nullable|string',
            'agency_id' => 'nullable|exists:agencies,id',
        ]);

        Bus::create($request->only(['immatriculation', 'nombre_places', 'classe', 'agency_id']));

        return back()->with('success', 'Bus ajouté avec succès.');
    }

    public function busUpdate(Request $request, Bus $bus)
    {
        $request->validate([
            'statut' => 'required|in:actif,en_maintenance,hors_service',
        ]);

        $bus->update(['statut' => $request->statut]);

        return back()->with('success', 'Statut du bus mis à jour.');
    }

    public function reservations()
    {
        $reservations = Reservation::with('trip', 'payment')
            ->latest()
            ->paginate(15);

        return view('admin.reservations', compact('reservations'));
    }

    public function chiffreAffaires()
    {
        $paiements = Payment::with('reservation')
            ->latest()
            ->paginate(15);

        $total = Payment::where('statut', 'valide')->sum('montant');

        $totalParMethode = Payment::where('statut', 'valide')
            ->selectRaw('mode_paiement, SUM(montant) as total')
            ->groupBy('mode_paiement')
            ->pluck('total', 'mode_paiement');

        return view('admin.chiffre-affaires', compact('paiements', 'total', 'totalParMethode'));
    }
}