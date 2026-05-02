<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use App\Models\Intervention;
use App\Models\Rapport;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TechnicienController extends Controller
{
    public function dashboard(): View
    {
        $technicien = $this->currentTechnicien();
        $baseQuery = $technicien->taches();

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'en_attente' => (clone $baseQuery)->where('statut', 'en attente')->count(),
            'en_cours' => (clone $baseQuery)->where('statut', 'en cours')->count(),
            'terminees' => (clone $baseQuery)->where('statut', 'terminé')->count(),
            'en_retard' => (clone $baseQuery)
                ->where('statut', '!=', 'terminé')
                ->whereDate('date_deadline', '<', now()->toDateString())
                ->count(),
        ];
        $stats['completion'] = $stats['total'] > 0
            ? (int) round(($stats['terminees'] / $stats['total']) * 100)
            : 0;

        $nextTaches = $technicien->taches()
            ->with('machine')
            ->whereIn('statut', ['en attente', 'en cours'])
            ->orderByRaw("CASE WHEN priorite = 'haute' THEN 0 WHEN priorite = 'moyenne' THEN 1 ELSE 2 END")
            ->orderBy('date_deadline')
            ->take(5)
            ->get();

        $recentCompleted = $technicien->taches()
            ->with('machine')
            ->where('statut', 'terminé')
            ->latest('updated_at')
            ->take(4)
            ->get();

        return view('technicien.dashboard', compact('technicien', 'stats', 'nextTaches', 'recentCompleted'));
    }

    public function taches(Request $request): View
    {
        $technicien = $this->currentTechnicien();

        $statusMap = [
            'pending' => 'en attente',
            'waiting' => 'en attente',
            'active' => 'en cours',
            'progress' => 'en cours',
            'done' => 'terminé',
            'termine' => 'terminé',
            'terminé' => 'terminé',
        ];

        $selectedStatus = $request->query('statut', 'all');
        $statusValue = $statusMap[$selectedStatus] ?? null;
        $search = trim((string) $request->query('q', ''));

        $countsQuery = $technicien->taches();
        $counts = [
            'all' => (clone $countsQuery)->count(),
            'pending' => (clone $countsQuery)->where('statut', 'en attente')->count(),
            'active' => (clone $countsQuery)->where('statut', 'en cours')->count(),
            'done' => (clone $countsQuery)->where('statut', 'terminé')->count(),
            'late' => (clone $countsQuery)
                ->where('statut', '!=', 'terminé')
                ->whereDate('date_deadline', '<', now()->toDateString())
                ->count(),
        ];

        $tachesQuery = $technicien->taches()
            ->with(['machine', 'interventions.rapport']);

        if ($selectedStatus === 'late') {
            $tachesQuery
                ->where('statut', '!=', 'terminé')
                ->whereDate('date_deadline', '<', now()->toDateString());
        } elseif ($statusValue) {
            $tachesQuery->where('statut', $statusValue);
        }

        if ($search !== '') {
            $tachesQuery->where(function ($query) use ($search) {
                $query->where('titre', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('machine', fn ($machineQuery) => $machineQuery->where('nom', 'like', "%{$search}%"));
            });
        }

        $taches = $tachesQuery
            ->orderByRaw("CASE WHEN statut = 'en cours' THEN 0 WHEN statut = 'en attente' THEN 1 ELSE 2 END")
            ->orderByRaw("CASE WHEN priorite = 'haute' THEN 0 WHEN priorite = 'moyenne' THEN 1 ELSE 2 END")
            ->orderBy('date_deadline')
            ->paginate(8)
            ->withQueryString();

        $machines = Machine::orderBy('nom')->get();

        return view('technicien.taches.index', compact('technicien', 'taches', 'machines', 'counts', 'selectedStatus', 'search'));
    }

    public function updateStatut(Request $request, Tache $tache)
    {
        $validated = $request->validate([
            'statut' => 'required|in:en attente,en cours,terminé'
        ]);

        $technicien = auth()->user()->technicien;
        abort_unless($technicien && $tache->technicien_id === $technicien->id, 403);

        $tache->update(['statut' => $validated['statut']]);

        if ($validated['statut'] === 'terminé') {
            Intervention::where('tache_id', $tache->id)
                ->whereNull('date_fin')
                ->update([
                    'statut' => 'terminee',
                    'date_fin' => now(),
                ]);
        }

        return redirect()->back()->with('success', 'Statut mis à jour.');
    }

    public function storeRapport(Request $request, Tache $tache)
    {
        $validated = $request->validate([
            'machine_id' => 'nullable|exists:machines,id',
            'contenu' => 'required|string',
        ]);

        $technicien = auth()->user()->technicien;
        abort_unless($technicien && $tache->technicien_id === $technicien->id, 403);

        $machineId = $validated['machine_id'] ?? $tache->machine_id;

        if (! $machineId) {
            return back()
                ->withErrors(['machine_id' => 'Veuillez sélectionner une machine.'])
                ->withInput();
        }

        // Créer l'intervention
        $intervention = Intervention::create([
            'machine_id' => $machineId,
            'technicien_id' => $technicien->id,
            'tache_id' => $tache->id,
            'description' => $validated['contenu'],
            'statut' => 'terminee',
            'date_debut' => now()->subHours(2), // Example
            'date_fin' => now()
        ]);

        // Créer le rapport
        $rapport = Rapport::create([
            'intervention_id' => $intervention->id,
            'contenu' => $request->contenu
        ]);

        // Finaliser la tâche
        $tache->update(['statut' => 'terminé']);

        return redirect()
            ->route('technicien.dashboard')
            ->with('success', 'Rapport soumis et tâche clôturée.');
    }

    private function currentTechnicien()
    {
        $technicien = auth()->user()?->technicien;

        abort_unless($technicien, 403);

        return $technicien;
    }
}
