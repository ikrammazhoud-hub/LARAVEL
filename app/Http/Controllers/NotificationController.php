<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\RedirectResponse;
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
        $notificationLayout = auth()->user()->role === 'technicien'
            ? 'layouts.technicien'
            : 'layouts.admin';
        $notificationRoutePrefix = auth()->user()->role === 'technicien'
            ? 'technicien.notifications'
            : 'admin.notifications';

        return view('admin.notifications.index', compact('notifications', 'nonLues', 'notificationLayout', 'notificationRoutePrefix'));
    }

    /**
     * Marque une notification spécifique comme lue.
     */
    public function marquerLue(int $id): RedirectResponse
    {
        $this->authorizeNotificationOwner($id);
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
        $this->authorizeNotificationOwner($id);
        $this->notificationService->supprimer($id);

        return back()->with('success', 'Notification supprimée.');
    }

    private function authorizeNotificationOwner(int $id): void
    {
        $notification = Notification::findOrFail($id);

        abort_unless($notification->user_id === auth()->id(), 403);
    }
}
