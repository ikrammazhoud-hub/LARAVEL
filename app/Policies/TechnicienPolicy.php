<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Technicien;

class TechnicienPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Technicien $technicien): bool
    {
        return $user->role === 'admin' || $user->technicien?->id === $technicien->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Technicien $technicien): bool
    {
        return $user->role === 'admin' || $user->technicien?->id === $technicien->id;
    }

    public function delete(User $user, Technicien $technicien): bool
    {
        return $user->role === 'admin';
    }
}
