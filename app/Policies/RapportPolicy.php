<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Rapport;

class RapportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Rapport $rapport): bool
    {
        return $user->role === 'admin' || $user->technicien?->id === $rapport->intervention->technicien_id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'technicien';
    }

    public function update(User $user, Rapport $rapport): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Rapport $rapport): bool
    {
        return $user->role === 'admin';
    }
}
