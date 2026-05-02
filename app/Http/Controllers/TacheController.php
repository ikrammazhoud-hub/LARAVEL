<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use App\Models\Machine;
use App\Models\Technicien;
use App\Services\TacheService;
use App\Services\NotificationService;
use App\Http\Requests\TacheRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Contrôleur CRUD complet pour les Tâches.
 */
class TacheController extends Controller
{
    protected TacheService $tacheService;
    protected NotificationService $notificationService;

    public function __construct(
        TacheService $tacheService,
        NotificationService $notificationService
    ) {
        $this->tacheService        = $tacheService;
        $this->notificationService = $notificationService;
    }

    /** Liste toutes les tâches (paginées). */
    public function index(): View
    {
        $taches      = $this->tacheService->getAllTaches(15);
        $techniciens = Technicien::with('user')->get();
        $machines    = Machine::orderBy('nom')->get();

        return view('admin.taches.index', compact('taches', 'techniciens', 'machines'));
    }

    /** Formulaire de création. */
    public function create(): View
    {
        $techniciens = Technicien::with('user')->get();
        $machines    = Machine::orderBy('nom')->get();

        return view('admin.taches.create', compact('techniciens', 'machines'));
    }

    /** Enregistre une nouvelle tâche et notifie le technicien. */
    public function store(TacheRequest $request): RedirectResponse
    {
        $tache = $this->tacheService->createTache(
            array_merge($request->validated(), ['statut' => 'en attente'])
        );

        // Notifier le technicien de sa nouvelle tâche
        $technicien = Technicien::find($tache->technicien_id);
        if ($technicien?->user) {
            $this->notificationService->notifierTacheAssignee(
                $technicien->user->id,
                $tache->titre
            );
        }

        return redirect()
            ->route('admin.taches.index')
            ->with('success', 'Tâche créée et technicien notifié.');
    }

    /** Détail d'une tâche. */
    public function show(int $id): View
    {
        $tache = $this->tacheService->getTacheById($id);
        return view('admin.taches.show', compact('tache'));
    }

    /** Formulaire de modification. */
    public function edit(int $id): View
    {
        $tache       = $this->tacheService->getTacheById($id);
        $techniciens = Technicien::with('user')->get();
        $machines    = Machine::orderBy('nom')->get();

        return view('admin.taches.edit', compact('tache', 'techniciens', 'machines'));
    }

    /** Met à jour une tâche. */
    public function update(TacheRequest $request, int $id): RedirectResponse
    {
        $this->tacheService->updateTache($id, $request->validated());

        return redirect()
            ->route('admin.taches.index')
            ->with('success', 'Tâche mise à jour avec succès.');
    }

    /** Supprime une tâche. */
    public function destroy(int $id): RedirectResponse
    {
        $this->tacheService->deleteTache($id);

        return redirect()
            ->route('admin.taches.index')
            ->with('success', 'Tâche supprimée.');
    }
}
