<?php

namespace Database\Seeders;

use App\Models\Machine;
use Illuminate\Database\Seeder;

class MachineSeeder extends Seeder
{
    public function run(): void
    {
        Machine::create([
            'nom' => 'Presse Hydraulique #1',
            'etat' => 'ACTIF',
            'localisation' => 'Atelier A - Zone 1',
        ]);

        Machine::create([
            'nom' => 'Tour CNC #3',
            'etat' => 'ACTIF',
            'localisation' => 'Atelier B - Zone 2',
        ]);

        Machine::create([
            'nom' => 'Fraiseuse #5',
            'etat' => 'PANNE',
            'localisation' => 'Atelier A - Zone 3',
        ]);

        Machine::create([
            'nom' => 'Compresseur #2',
            'etat' => 'MAINTENANCE',
            'localisation' => 'Atelier C - Zone 1',
        ]);

        Machine::create([
            'nom' => 'Robot de Soudure #1',
            'etat' => 'ACTIF',
            'localisation' => 'Atelier B - Zone 4',
        ]);
    }
}
