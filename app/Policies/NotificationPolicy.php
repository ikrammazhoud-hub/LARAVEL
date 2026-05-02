<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy d'autorisation pour les Notifications.
 */
class NotificationPolicy
{
    use HandlesAuthorization;

    /** Un utilisateur voit seulement ses propres notifications. */
    public function view(User $user, Notification $notification): bool
    {
        return $user->id === $notification->user_id;
    }

    /** Un utilisateur peut marquer comme lue seulement ses notifications. */
    public function update(User $user, Notification $notification): bool
    {
        return $user->id === $notification->user_id;
    }

    /** Un utilisateur ne peut supprimer que ses propres notifications. */
    public function delete(User $user, Notification $notification): bool
    {
        return $user->id === $notification->user_id;
    }
}
