<?php

namespace App\Repositories;

use App\Models\Notification;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Repository pour les opérations de données sur les Notifications.
 */
class NotificationRepository
{
    /**
     * Retourne toutes les notifications d'un utilisateur (paginées).
     */
    public function allForUser(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return Notification::pourUtilisateur($userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Retourne les notifications non lues d'un utilisateur.
     */
    public function nonLuesPourUser(int $userId): Collection
    {
        return Notification::pourUtilisateur($userId)
            ->nonLues()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Compte les notifications non lues.
     */
    public function countNonLues(int $userId): int
    {
        return Notification::pourUtilisateur($userId)->nonLues()->count();
    }

    /**
     * Crée une notification.
     */
    public function create(array $data): Notification
    {
        return Notification::create($data);
    }

    /**
     * Marque une notification comme lue.
     */
    public function marquerLue(int $id): Notification
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['lu' => true]);
        return $notification;
    }

    /**
     * Marque toutes les notifications d'un utilisateur comme lues.
     */
    public function marquerToutesLues(int $userId): int
    {
        return Notification::pourUtilisateur($userId)
            ->nonLues()
            ->update(['lu' => true]);
    }

    /**
     * Supprime une notification.
     */
    public function delete(int $id): bool
    {
        return Notification::findOrFail($id)->delete();
    }
}
