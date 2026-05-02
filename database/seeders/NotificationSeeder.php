<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Notification;
use App\Models\User;

/**
 * Seeder de notifications de démonstration.
 */
class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            $this->command->warn('Aucun utilisateur trouvé.');
            return;
        }

        $types = [
            [
                'type'    => 'tache_assignee',
                'titre'   => 'Nouvelle tâche assignée',
                'message' => 'Une nouvelle tâche de maintenance préventive vous a été assignée : « Révision mensuelle Compresseur A ».',
                'lu'      => false,
            ],
            [
                'type'    => 'statut_change',
                'titre'   => 'Statut de tâche mis à jour',
                'message' => 'Ali Ben Salah a mis à jour la tâche « Inspection électrique » → en cours.',
                'lu'      => true,
            ],
            [
                'type'    => 'alerte_machine',
                'titre'   => 'Alerte machine',
                'message' => 'La machine « Tour CN-5 » est signalée en PANNE. Intervention requise.',
                'lu'      => false,
            ],
            [
                'type'    => 'tache_assignee',
                'titre'   => 'Rappel : date limite approche',
                'message' => 'La tâche « Nettoyage filtre presse hydraulique » doit être terminée avant demain.',
                'lu'      => false,
            ],
        ];

        foreach ($users->take(3) as $user) {
            foreach ($types as $notif) {
                Notification::create([
                    'user_id' => $user->id,
                    ...$notif,
                    'data' => [],
                ]);
            }
        }

        $this->command->info('NotificationSeeder : notifications créées pour ' . min($users->count(), 3) . ' utilisateurs.');
    }
}
