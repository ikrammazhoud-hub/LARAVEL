<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Machine;

class MachinePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, Machine $machine): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Machine $machine): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, Machine $machine): bool
    {
        return $user->role === 'admin';
    }
}
