<?php

namespace App\Policies;

use App\Models\Intervention;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy d'autorisation pour les Interventions.
 */
class InterventionPolicy
{
    use HandlesAuthorization;

    /** Seul l'admin peut lister toutes les interventions. */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    /** Admin voit tout ; technicien voit ses propres interventions. */
    public function view(User $user, Intervention $intervention): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->technicien?->id === $intervention->technicien_id;
    }

    /** Seul l'admin peut créer une intervention. */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /** Seul l'admin peut modifier une intervention. */
    public function update(User $user, Intervention $intervention): bool
    {
        return $user->role === 'admin';
    }

    /** Seul l'admin peut supprimer une intervention. */
    public function delete(User $user, Intervention $intervention): bool
    {
        return $user->role === 'admin';
    }
}
