<?php

namespace Database\Seeders;

use App\Models\Tache;
use App\Models\Technicien;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TacheSeeder extends Seeder
{
    public function run(): void
    {
        $techniciens = Technicien::all();

        $taches = [
            [
                'titre' => 'Maintenance préventive Presse #1',
                'description' => 'Vérification des niveaux d\'huile et des joints hydrauliques',
                'priorite' => 'moyenne',
                'statut' => 'en attente',
                'date_deadline' => Carbon::now()->addDays(5),
            ],
            [
                'titre' => 'Réparation Fraiseuse #5',
                'description' => 'Diagnostic et remplacement de la broche défectueuse',
                'priorite' => 'haute',
                'statut' => 'en cours',
                'date_deadline' => Carbon::now()->addDays(2),
            ],
            [
                'titre' => 'Calibrage Tour CNC #3',
                'description' => 'Recalibrage des axes X et Y après maintenance',
                'priorite' => 'basse',
                'statut' => 'terminé',
                'date_deadline' => Carbon::now()->subDays(3),
            ],
            [
                'titre' => 'Inspection Compresseur #2',
                'description' => 'Vérification complète du système de compression',
                'priorite' => 'haute',
                'statut' => 'en attente',
                'date_deadline' => Carbon::now()->addDays(1),
            ],
        ];

        foreach ($taches as $index => $tacheData) {
            Tache::create([
                'technicien_id' => $techniciens[$index % $techniciens->count()]->id,
                ...$tacheData,
            ]);
        }
    }
}
