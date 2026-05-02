<?php

namespace App\Http\Controllers;

use App\Models\Intervention;
use App\Services\InterventionService;
use App\Http\Requests\InterventionRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Contrôleur CRUD des Interventions (espace admin).
 */
class InterventionController extends Controller
{
    protected InterventionService $interventionService;

    public function __construct(InterventionService $interventionService)
    {
        $this->interventionService = $interventionService;
    }

    /**
     * Liste toutes les interventions.
     */
    public function index(): View
    {
        $interventions = $this->interventionService->getAllInterventions(15);

        return view('admin.interventions.index', compact('interventions'));
    }

    /**
     * Formulaire de création d'une intervention.
     */
    public function create(): View
    {
        $machines    = \App\Models\Machine::orderBy('nom')->get();
        $techniciens = \App\Models\Technicien::with('user')->get();
        $taches      = \App\Models\Tache::where('statut', '!=', 'terminé')->orderBy('titre')->get();

        return view('admin.interventions.create', compact('machines', 'techniciens', 'taches'));
    }

    /**
     * Enregistre une nouvelle intervention.
     */
    public function store(InterventionRequest $request): RedirectResponse
    {
        $this->interventionService->createIntervention($request->validated());

        return redirect()
            ->route('admin.interventions.index')
            ->with('success', 'Intervention créée avec succès.');
    }

    /**
     * Affiche le détail d'une intervention.
     */
    public function show(int $id): View
    {
        $intervention = $this->interventionService->getInterventionById($id);

        return view('admin.interventions.show', compact('intervention'));
    }

    /**
     * Formulaire d'édition d'une intervention.
     */
    public function edit(int $id): View
    {
        $intervention = $this->interventionService->getInterventionById($id);
        $machines     = \App\Models\Machine::orderBy('nom')->get();
        $techniciens  = \App\Models\Technicien::with('user')->get();

        return view('admin.interventions.edit', compact('intervention', 'machines', 'techniciens'));
    }

    /**
     * Met à jour une intervention.
     */
    public function update(InterventionRequest $request, int $id): RedirectResponse
    {
        $this->interventionService->updateIntervention($id, $request->validated());

        return redirect()
            ->route('admin.interventions.index')
            ->with('success', 'Intervention mise à jour avec succès.');
    }

    /**
     * Supprime une intervention.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->interventionService->deleteIntervention($id);

        return redirect()
            ->route('admin.interventions.index')
            ->with('success', 'Intervention supprimée.');
    }

    /**
     * Clôture une intervention (statut → terminée).
     */
    public function cloturer(int $id): RedirectResponse
    {
        $this->interventionService->cloturerIntervention($id);

        return redirect()
            ->route('admin.interventions.show', $id)
            ->with('success', 'Intervention clôturée avec succès.');
    }
}
