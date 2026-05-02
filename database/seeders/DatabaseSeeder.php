<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Technicien;
use App\Models\Machine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder principal — orchestre tous les seeders dans l'ordre correct.
 */
class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ── 1. Admin ──────────────────────────────────────────────────────────
        User::firstOrCreate(
            ['email' => 'admin@atelier.com'],
            [
                'name'     => 'Responsable Atelier',
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // ── 2. Techniciens ────────────────────────────────────────────────────
        $techniciens = [
            ['name' => 'Ali Ben Salah',   'email' => 'ali@atelier.com',   'specialite' => 'Électromécanique',  'telephone' => '0600010001', 'matricule' => 'TEC-001'],
            ['name' => 'Sara Mansouri',   'email' => 'sara@atelier.com',  'specialite' => 'Hydraulique',        'telephone' => '0600010002', 'matricule' => 'TEC-002'],
            ['name' => 'Karim Zouari',    'email' => 'karim@atelier.com', 'specialite' => 'Automatisme',       'telephone' => '0600010003', 'matricule' => 'TEC-003'],
            ['name' => 'Nadia Trabelsi',  'email' => 'nadia@atelier.com', 'specialite' => 'Mécanique générale','telephone' => '0600010004', 'matricule' => 'TEC-004'],
        ];

        foreach ($techniciens as $t) {
            $user = User::firstOrCreate(
                ['email' => $t['email']],
                [
                    'name'     => $t['name'],
                    'password' => Hash::make('password'),
                    'role'     => 'technicien',
                ]
            );

            Technicien::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'specialite' => $t['specialite'],
                    'telephone'  => $t['telephone'],
                    'matricule'  => $t['matricule'],
                    'disponible' => true,
                ]
            );
        }

        // ── 3. Machines ───────────────────────────────────────────────────────
        $machines = [
            ['nom' => 'Presse Hydraulique 50T',        'etat' => 'ACTIF',       'localisation' => 'Zone A - Secteur 1', 'numero_serie' => 'PH-2021-001'],
            ['nom' => 'Tour Numérique CNC',             'etat' => 'MAINTENANCE', 'localisation' => 'Zone B - Secteur 2', 'numero_serie' => 'TNC-2020-002'],
            ['nom' => 'Fraiseuse Industrielle',         'etat' => 'ACTIF',       'localisation' => 'Zone A - Secteur 3', 'numero_serie' => 'FI-2022-003'],
            ['nom' => 'Compresseur Air Haute Pression', 'etat' => 'PANNE',       'localisation' => 'Zone C - Secteur 1', 'numero_serie' => 'CAP-2019-004'],
            ['nom' => 'Robot Soudeur KUKA',             'etat' => 'ACTIF',       'localisation' => 'Zone B - Secteur 4', 'numero_serie' => 'RSK-2023-005'],
            ['nom' => 'Perceuse à Colonne',             'etat' => 'MAINTENANCE', 'localisation' => 'Zone A - Secteur 2', 'numero_serie' => 'PC-2020-006'],
        ];

        foreach ($machines as $m) {
            Machine::firstOrCreate(['numero_serie' => $m['numero_serie']], $m);
        }

        // ── 4. Seeders métier ─────────────────────────────────────────────────
        $this->call([
            TechnicienSeeder::class,
            MachineSeeder::class,
            TacheSeeder::class,
            InterventionSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}
