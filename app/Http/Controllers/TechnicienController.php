<?php

namespace App\Http\Controllers;

use App\Models\Tache;
use App\Models\Intervention;
use App\Models\Rapport;
use Illuminate\Http\Request;

class TechnicienController extends Controller
{
    public function dashboard()
    {
        $technicien = auth()->user()->technicien;
        $taches = $technicien ? $technicien->taches()->orderBy('date_deadline')->get() : collect();

        return view('technicien.dashboard', compact('taches'));
    }

    public function updateStatut(Request $request, Tache $tache)
    {
        $request->validate([
            'statut' => 'required|in:en attente,en cours,terminé'
        ]);

        $tache->update(['statut' => $request->statut]);

        if ($request->statut === 'terminé' && $tache->machine_id) {
             // Example simple logic if a machine_id was in Tache, 
             // but per specs Interventions are separate.
             // We can trigger an intervention creation here or handle separately.
        }

        return redirect()->back()->with('success', 'Statut mis à jour.');
    }

    public function storeRapport(Request $request, Tache $tache)
    {
        $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'contenu' => 'required|string',
        ]);

        $technicien = auth()->user()->technicien;

        // Créer l'intervention
        $intervention = Intervention::create([
            'machine_id' => $request->machine_id,
            'technicien_id' => $technicien->id,
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

        // Charger les relations pour l'affichage du PDF
        $rapport->load(['intervention.machine', 'intervention.technicien.user']);
        
        // Générer et afficher le PDF
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.rapport_pdf', compact('rapport'));
        return $pdf->stream('rapport_intervention_' . $rapport->id . '.pdf');
    }
}
