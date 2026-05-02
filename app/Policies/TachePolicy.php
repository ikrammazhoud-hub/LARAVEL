<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Tache;

class TachePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin' || $user->role === 'technicien';
    }

    public function view(User $user, Tache $tache): bool
    {
        return $user->role === 'admin' || $user->technicien?->id === $tache->technicien_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Tache $tache): bool
    {
        return $user->role === 'admin' || $user->technicien?->id === $tache->technicien_id;
    }

    public function delete(User $user, Tache $tache): bool
    {
        return $user->role === 'admin';
    }

    public function updateStatut(User $user, Tache $tache): bool
    {
        return $user->role === 'admin' || $user->technicien?->id === $tache->technicien_id;
    }
}
