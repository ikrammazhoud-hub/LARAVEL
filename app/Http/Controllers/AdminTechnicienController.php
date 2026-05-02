<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Technicien;
use App\Services\TechnicienService;
use App\Http\Requests\TechnicienRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Contrôleur CRUD complet pour les Techniciens (espace admin).
 */
class AdminTechnicienController extends Controller
{
    protected TechnicienService $technicienService;

    public function __construct(TechnicienService $technicienService)
    {
        $this->technicienService = $technicienService;
    }

    /** Liste tous les techniciens. */
    public function index(): View
    {
        $techniciens = $this->technicienService->getAllTechniciens(15);
        return view('admin.techniciens.index', compact('techniciens'));
    }

    /** Formulaire de création. */
    public function create(): View
    {
        return view('admin.techniciens.create');
    }

    /** Enregistre un nouveau technicien + son compte utilisateur. */
    public function store(TechnicienRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'technicien',
        ]);

        Technicien::create([
            'user_id'    => $user->id,
            'specialite' => $validated['specialite'],
            'telephone'  => $validated['telephone'],
            'matricule'  => $validated['matricule'] ?? null,
            'disponible' => true,
        ]);

        return redirect()
            ->route('admin.techniciens.index')
            ->with('success', 'Technicien créé avec succès.');
    }

    /** Détail d'un technicien. */
    public function show(Technicien $technicien): View
    {
        $technicien->load(['user', 'taches', 'interventions.machine']);
        return view('admin.techniciens.show', compact('technicien'));
    }

    /** Formulaire de modification. */
    public function edit(Technicien $technicien): View
    {
        $technicien->load('user');
        return view('admin.techniciens.edit', compact('technicien'));
    }

    /** Met à jour le profil du technicien. */
    public function update(TechnicienRequest $request, Technicien $technicien): RedirectResponse
    {
        $validated = $request->validated();

        $technicien->user->update([
            'name'  => $validated['name'],
            'email' => $validated['email'],
        ]);

        $technicien->update([
            'specialite' => $validated['specialite'],
            'telephone'  => $validated['telephone'],
            'matricule'  => $validated['matricule'] ?? $technicien->matricule,
            'disponible' => $validated['disponible'] ?? $technicien->disponible,
        ]);

        return redirect()
            ->route('admin.techniciens.index')
            ->with('success', 'Technicien mis à jour avec succès.');
    }

    /** Supprime le technicien et son compte utilisateur. */
    public function destroy(Technicien $technicien): RedirectResponse
    {
        $technicien->user->delete(); // cascade vers technicien via FK
        return redirect()
            ->route('admin.techniciens.index')
            ->with('success', 'Technicien supprimé.');
    }
}
