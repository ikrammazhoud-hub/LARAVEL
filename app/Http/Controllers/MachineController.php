<?php

namespace App\Http\Controllers;

use App\Models\Machine;
use App\Services\MachineService;
use App\Http\Requests\MachineRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Contrôleur CRUD complet pour les Machines.
 * Suit le pattern : FormRequest → Service → Repository.
 */
class MachineController extends Controller
{
    protected MachineService $machineService;

    public function __construct(MachineService $machineService)
    {
        $this->machineService = $machineService;
    }

    /** Liste toutes les machines (paginées). */
    public function index(): View
    {
        $machines = $this->machineService->getAllMachines(15);
        return view('admin.machines.index', compact('machines'));
    }

    /** Formulaire de création. */
    public function create(): View
    {
        return view('admin.machines.create');
    }

    /** Enregistre une nouvelle machine. */
    public function store(MachineRequest $request): RedirectResponse
    {
        $this->machineService->createMachine($request->validated());

        return redirect()
            ->route('admin.machines.index')
            ->with('success', 'Machine créée avec succès.');
    }

    /** Détail d'une machine. */
    public function show(int $id): View
    {
        $machine        = $this->machineService->getMachineById($id);
        $interventions  = $machine->interventions()->with('technicien.user')->latest()->take(5)->get();

        return view('admin.machines.show', compact('machine', 'interventions'));
    }

    /** Formulaire de modification. */
    public function edit(int $id): View
    {
        $machine = $this->machineService->getMachineById($id);
        return view('admin.machines.edit', compact('machine'));
    }

    /** Met à jour une machine. */
    public function update(MachineRequest $request, int $id): RedirectResponse
    {
        $this->machineService->updateMachine($id, $request->validated());

        return redirect()
            ->route('admin.machines.index')
            ->with('success', 'Machine mise à jour avec succès.');
    }

    /** Supprime une machine. */
    public function destroy(int $id): RedirectResponse
    {
        $this->machineService->deleteMachine($id);

        return redirect()
            ->route('admin.machines.index')
            ->with('success', 'Machine supprimée avec succès.');
    }
}
