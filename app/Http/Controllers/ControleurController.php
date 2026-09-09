<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ControleurController extends Controller
{
    public function dashboard()
    {
        $departs_du_jour = \App\Models\Trip::with(['bus', 'reservations'])
            ->where('date_depart', today())
            ->orderBy('heure_depart')
            ->get();

        return view('controleur.dashboard', compact('departs_du_jour'));
    }

    public function scanner()
    {
        return view('controleur.scanner');
    }

    public function verifier(Request $request)
    {
        $reference = trim($request->input('reference', ''));

        if (preg_match('/[0-9]+/', $reference, $matches)) {
            $reservation = Reservation::with('trip.bus')
                ->find($matches[0]);
        } else {
            $reservation = Reservation::with('trip.bus')
                ->where('reference', $reference)
                ->first();
        }

        if (! $reservation) {
            return back()->with('error', 'Billet introuvable.');
        }

        return view('controleur.resultat', compact('reservation'));
    }

    public function validerEmbarquement(Reservation $reservation)
    {
        abort_unless(in_array($reservation->statut, ['confirmee', 'utilisee']), 403, 'Billet non valide pour l\'embarquement.');

        $reservation->update(['statut' => 'utilisee']);

        return back()->with('success', "Embarquement validé pour {$reservation->client_nom} ({$reservation->reference}).");
    }
}