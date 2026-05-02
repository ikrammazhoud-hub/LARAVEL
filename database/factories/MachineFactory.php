<?php

namespace Database\Factories;

use App\Models\Machine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory pour générer des machines de test.
 */
class MachineFactory extends Factory
{
    protected $model = Machine::class;

    public function definition(): array
    {
        return [
            'nom'          => $this->faker->randomElement([
                'Presse Hydraulique', 'Tour CNC', 'Fraiseuse', 'Compresseur', 'Robot Soudeur',
                'Perceuse Colonne', 'Rectifieuse', 'Tour Conventionnel', 'Scie Circulaire',
            ]) . ' ' . $this->faker->numerify('##'),
            'etat'         => $this->faker->randomElement(['ACTIF', 'PANNE', 'MAINTENANCE']),
            'localisation' => 'Zone ' . $this->faker->randomElement(['A', 'B', 'C']) . ' - Secteur ' . $this->faker->numberBetween(1, 6),
            'numero_serie' => strtoupper($this->faker->bothify('??-####-???')),
            'description'  => $this->faker->optional()->sentence(10),
        ];
    }

    public function actif(): static
    {
        return $this->state(['etat' => 'ACTIF']);
    }

    public function enPanne(): static
    {
        return $this->state(['etat' => 'PANNE']);
    }

    public function enMaintenance(): static
    {
        return $this->state(['etat' => 'MAINTENANCE']);
    }
}
