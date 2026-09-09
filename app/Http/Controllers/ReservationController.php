<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Services\AlternativeDatesService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function create(Request $request, AlternativeDatesService $alternativesService)
    {
        $trip = \App\Models\Trip::with('bus')->findOrFail($request->trip_id);

        $suggestion = $alternativesService->suggerer($trip);

        if ($suggestion['complet']) {
            return view('reservation.complet', [
                'trip' => $trip,
                'alternatives' => $suggestion['alternatives'],
                'message' => $suggestion['message'],
            ]);
        }

        return view('reservation.form', compact('trip'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'client_nom' => 'required|string|max:255',
            'client_telephone' => 'required|string|max:20',
            'nombre_places' => 'required|integer|min:1|max:10',
        ]);

        $trip = \App\Models\Trip::with('bus')->findOrFail($validated['trip_id']);

        if ($validated['nombre_places'] > $trip->placesRestantes()) {
            return back()->withErrors([
                'nombre_places' => "Seulement {$trip->placesRestantes()} place(s) restante(s) pour ce trajet.",
            ])->withInput();
        }

        $validated['reference'] = 'RES-' . strtoupper(bin2hex(random_bytes(4)));
        $validated['statut'] = 'en_attente';
        $validated['user_id'] = auth()->id();

        $reservation = Reservation::create($validated);

        return redirect()->route('payment.form', ['reservation' => $reservation->id]);
    }
}