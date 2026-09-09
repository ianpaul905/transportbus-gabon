<?php

namespace App\Services;

use App\Models\Trip;

class AlternativeDatesService
{
    /**
     * Algorithme de suggestion de dates alternatives lorsque le bus est complet.
     *
     * SI Places_Disponibles(Trajet_Choisi) == 0 ALORS
     *     Rechercher Prochains_Trajets DANS la même semaine
     *     Afficher : "Bus complet le [Date]. Prochains départs
     *                disponibles : [Dates_Alternatives]"
     * SINON
     *     Autoriser la réservation
     * FIN SI
     */
    public function suggerer(Trip $trip, int $limite = 5): array
    {
        if (! $trip->estComplet()) {
            return [
                'complet' => false,
                'alternatives' => collect(),
                'message' => 'Des places sont disponibles pour ce trajet.',
            ];
        }

        $alternatives = Trip::query()
            ->where('ville_depart', $trip->ville_depart)
            ->where('ville_arrivee', $trip->ville_arrivee)
            ->where('date_depart', '>=', $trip->date_depart)
            ->where('id', '!=', $trip->id)
            ->whereHas('bus', fn ($q) => $q->where('statut', 'actif'))
            ->orderBy('date_depart')
            ->orderBy('heure_depart')
            ->with('bus')
            ->get()
            ->filter(fn (Trip $t) => ! $t->estComplet())
            ->take($limite)
            ->values();

        $dates = $alternatives->map(fn (Trip $t) => $t->date_depart->format('d/m/Y'))->unique()->implode(', ');

        return [
            'complet' => true,
            'alternatives' => $alternatives,
            'message' => $alternatives->isEmpty()
                ? "Bus complet le {$trip->date_depart->format('d/m/Y')}. Aucun autre départ disponible dans les prochains jours."
                : "Bus complet le {$trip->date_depart->format('d/m/Y')}. Prochains départs disponibles : {$dates}.",
        ];
    }
}