<?php

namespace Database\Factories;

use App\Models\Tache;
use App\Models\Technicien;
use App\Models\Machine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory pour générer des tâches de test.
 */
class TacheFactory extends Factory
{
    protected $model = Tache::class;

    public function definition(): array
    {
        return [
            'technicien_id' => Technicien::inRandomOrder()->first()?->id ?? 1,
            'machine_id'    => Machine::inRandomOrder()->first()?->id,
            'titre'         => $this->faker->randomElement([
                'Révision périodique',
                'Remplacement filtre',
                'Contrôle électrique',
                'Lubrification engrenages',
                'Inspection capteurs',
                'Nettoyage circuit hydraulique',
                'Vérification courroies',
                'Test de pression',
            ]) . ' — ' . $this->faker->word(),
            'description'   => $this->faker->optional()->paragraph(2),
            'priorite'      => $this->faker->randomElement(['basse', 'normale', 'haute', 'urgente']),
            'statut'        => $this->faker->randomElement(['en attente', 'en cours', 'terminé']),
            'date_deadline' => $this->faker->optional()->dateTimeBetween('now', '+30 days'),
        ];
    }

    public function urgente(): static
    {
        return $this->state(['priorite' => 'urgente', 'statut' => 'en attente']);
    }

    public function terminee(): static
    {
        return $this->state(['statut' => 'terminé']);
    }
}
