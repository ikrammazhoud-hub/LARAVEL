<?php

namespace App\Services;

use App\Models\Notification;
use App\Repositories\NotificationRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Service de gestion des notifications internes de l'application.
 */
class NotificationService
{
    protected NotificationRepository $repository;

    public function __construct(NotificationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Retourne toutes les notifications paginées d'un utilisateur.
     */
    public function getNotificationsUtilisateur(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return $this->repository->allForUser($userId, $perPage);
    }

    /**
     * Retourne les notifications non lues d'un utilisateur.
     */
    public function getNotificationsNonLues(int $userId): Collection
    {
        return $this->repository->nonLuesPourUser($userId);
    }

    /**
     * Compte les notifications non lues.
     */
    public function countNonLues(int $userId): int
    {
        return $this->repository->countNonLues($userId);
    }

    /**
     * Envoie une notification à un utilisateur.
     */
    public function envoyer(int $userId, string $type, string $titre, string $message, array $data = []): Notification
    {
        return $this->repository->create([
            'user_id' => $userId,
            'type'    => $type,
            'titre'   => $titre,
            'message' => $message,
            'lu'      => false,
            'data'    => $data,
        ]);
    }

    /**
     * Notifie un technicien d'une nouvelle tâche assignée.
     */
    public function notifierTacheAssignee(int $userId, string $titreTache): Notification
    {
        return $this->envoyer(
            userId : $userId,
            type   : 'tache_assignee',
            titre  : 'Nouvelle tâche assignée',
            message: "Une nouvelle tâche vous a été assignée : « {$titreTache} ».",
        );
    }

    /**
     * Notifie l'admin d'un changement de statut de tâche.
     */
    public function notifierChangementStatut(int $adminUserId, string $techNom, string $titreTache, string $statut): Notification
    {
        return $this->envoyer(
            userId : $adminUserId,
            type   : 'statut_change',
            titre  : 'Statut de tâche mis à jour',
            message: "{$techNom} a mis à jour la tâche « {$titreTache} » → {$statut}.",
        );
    }

    /**
     * Notifie d'une alerte machine (panne).
     */
    public function notifierAlerteMachine(int $userId, string $nomMachine): Notification
    {
        return $this->envoyer(
            userId : $userId,
            type   : 'alerte_machine',
            titre  : 'Alerte machine',
            message: "La machine « {$nomMachine} » est signalée en PANNE.",
        );
    }

    /**
     * Marque une notification comme lue.
     */
    public function marquerLue(int $id): Notification
    {
        return $this->repository->marquerLue($id);
    }

    /**
     * Marque toutes les notifications d'un utilisateur comme lues.
     */
    public function marquerToutesLues(int $userId): int
    {
        return $this->repository->marquerToutesLues($userId);
    }

    /**
     * Supprime une notification.
     */
    public function supprimer(int $id): bool
    {
        return $this->repository->delete($id);
    }
}
