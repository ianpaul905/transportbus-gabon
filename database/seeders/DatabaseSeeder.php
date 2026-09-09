<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrateur Principal',
            'email' => 'admin@transportbus.ga',
            'password' => bcrypt('password123'),
            'telephone' => '+241 07 00 00 01',
            'role' => 'admin',
        ]);

        $controleur = User::create([
            'name' => 'Contrôleur Major Transport',
            'email' => 'controleur@transportbus.ga',
            'password' => bcrypt('password123'),
            'telephone' => '+241 07 00 00 02',
            'role' => 'controleur',
        ]);

        $client = User::create([
            'name' => 'Client Démo',
            'email' => 'client@transportbus.ga',
            'password' => bcrypt('password123'),
            'telephone' => '+241 07 00 00 03',
            'role' => 'client',
        ]);

        $agency = Agency::create([
            'nom' => 'Major Transport',
            'ville' => 'Libreville',
            'adresse' => 'Gare routière de Nkembo',
            'telephone' => '+241 01 12 34 56',
        ]);

        $agency2 = Agency::create([
            'nom' => 'Transporteur Voyages',
            'ville' => 'Libreville',
            'adresse' => 'Croisement Bouet',
            'telephone' => '+241 01 98 76 54',
        ]);

        $buses = [
            Bus::create(['immatriculation' => 'AB-101', 'nombre_places' => 40, 'statut' => 'actif', 'agency_id' => $agency->id, 'classe' => 'VIP']),
            Bus::create(['immatriculation' => 'AB-102', 'nombre_places' => 35, 'statut' => 'actif', 'agency_id' => $agency->id, 'classe' => 'VIP']),
            Bus::create(['immatriculation' => 'AB-103', 'nombre_places' => 40, 'statut' => 'en_maintenance', 'agency_id' => $agency->id, 'classe' => null]),
            Bus::create(['immatriculation' => 'TV-201', 'nombre_places' => 45, 'statut' => 'actif', 'agency_id' => $agency2->id, 'classe' => null]),
            Bus::create(['immatriculation' => 'TV-202', 'nombre_places' => 38, 'statut' => 'actif', 'agency_id' => $agency2->id, 'classe' => 'VIP']),
        ];

        $trajets = [
            // Libreville -> Mouila
            ['Libreville', 'Mouila', 2, 5, 15000],
            ['Libreville', 'Mouila', 3, 5, 15000],
            ['Libreville', 'Mouila', 4, 5, 15000],
            ['Libreville', 'Mouila', 5, 6, 18000],
            ['Libreville', 'Lambaréné', 2, 4, 8000],
            ['Libreville', 'Lambaréné', 3, 4, 8000],
            ['Libreville', 'Oyem', 2, 7, 17000],
            ['Libreville', 'Oyem', 4, 7, 17000],
            ['Libreville', 'Franceville', 3, 8, 22000],
            ['Libreville', 'Franceville', 6, 8, 22000],
            ['Libreville', 'Port-Gentil', 2, 6, 16000],
            ['Libreville', 'Port-Gentil', 5, 6, 16000],
            ['Mouila', 'Libreville', 2, 10, 15000],
            ['Oyem', 'Libreville', 3, 9, 17000],
        ];

        foreach ($trajets as [$depart, $arrivee, $jours, $heure, $prix]) {
            $bus = $buses[array_rand($buses)];
            Trip::create([
                'ville_depart' => $depart,
                'ville_arrivee' => $arrivee,
                'date_depart' => now()->addDays($jours)->toDateString(),
                'heure_depart' => sprintf('%02d:00:00', $heure),
                'prix' => $prix,
                'bus_id' => $bus->id,
            ]);

            // Un second départ le même jour avec un autre bus
            if (in_array($depart . $arrivee, ['LibrevilleMouila', 'LibrevilleOyem', 'LibrevilleFranceville'])) {
                $bus2 = $buses[2];
                Trip::create([
                    'ville_depart' => $depart,
                    'ville_arrivee' => $arrivee,
                    'date_depart' => now()->addDays($jours)->toDateString(),
                    'heure_depart' => sprintf('%02d:00:00', $heure + 2),
                    'prix' => $prix,
                    'bus_id' => $bus2->id,
                ]);
            }
        }
    }
}