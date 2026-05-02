<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Contrôleur de gestion des notifications utilisateur.
 */
class NotificationController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Affiche toutes les notifications de l'utilisateur connecté.
     */
    public function index(): View
    {
        $userId        = auth()->id();
        $notifications = $this->notificationService->getNotificationsUtilisateur($userId);
        $nonLues       = $this->notificationService->countNonLues($userId);

        return view('admin.notifications.index', compact('notifications', 'nonLues'));
    }

    /**
     * Marque une notification spécifique comme lue.
     */
    public function marquerLue(int $id): RedirectResponse
    {
        $this->notificationService->marquerLue($id);

        return back()->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Marque toutes les notifications comme lues.
     */
    public function marquerToutesLues(): RedirectResponse
    {
        $this->notificationService->marquerToutesLues(auth()->id());

        return back()->with('success', 'Toutes les notifications ont été marquées comme lues.');
    }

    /**
     * Supprime une notification.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->notificationService->supprimer($id);

        return back()->with('success', 'Notification supprimée.');
    }
}
