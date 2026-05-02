<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Technicien;
use App\Models\Machine;
use App\Models\Tache;
use App\Models\Intervention;
use App\Models\Rapport;

/**
 * Seeder d'interventions de démonstration.
 */
class InterventionSeeder extends Seeder
{
    public function run(): void
    {
        $machines    = Machine::all();
        $techniciens = Technicien::all();
        $taches      = Tache::all();

        if ($machines->isEmpty() || $techniciens->isEmpty()) {
            $this->command->warn('Aucune machine ou technicien trouvé. Lancez d\'abord MachineSeeder et TechnicienSeeder.');
            return;
        }

        $interventions = [
            [
                'description' => 'Remplacement du filtre hydraulique et vérification des joints.',
                'statut'      => 'terminee',
                'date_debut'  => now()->subDays(10),
                'date_fin'    => now()->subDays(9),
            ],
            [
                'description' => 'Contrôle du circuit électrique principal et remplacement fusible.',
                'statut'      => 'terminee',
                'date_debut'  => now()->subDays(7),
                'date_fin'    => now()->subDays(6),
            ],
            [
                'description' => 'Lubrification des engrenages et réglage de la courroie de transmission.',
                'statut'      => 'en_cours',
                'date_debut'  => now()->subDay(),
                'date_fin'    => null,
            ],
            [
                'description' => 'Inspection thermique et nettoyage du système de refroidissement.',
                'statut'      => 'en_cours',
                'date_debut'  => now(),
                'date_fin'    => null,
            ],
        ];

        foreach ($interventions as $i => $data) {
            $intervention = Intervention::create([
                'machine_id'    => $machines->random()->id,
                'technicien_id' => $techniciens->random()->id,
                'tache_id'      => $taches->isNotEmpty() ? $taches->random()->id : null,
                ...$data,
            ]);

            // Crée un rapport pour les interventions terminées
            if ($data['statut'] === 'terminee') {
                Rapport::create([
                    'intervention_id' => $intervention->id,
                    'contenu'         => 'Intervention réalisée avec succès. ' . $data['description'],
                    'observations'    => 'Aucune anomalie supplémentaire détectée.',
                    'pieces_changees' => $i === 0 ? 'Filtre hydraulique (réf. FH-2024), joints toriques' : 'Fusible 16A',
                    'recommandations' => 'Prévoir une révision dans 3 mois.',
                ]);
            }
        }

        $this->command->info('InterventionSeeder : ' . count($interventions) . ' interventions créées.');
    }
}
