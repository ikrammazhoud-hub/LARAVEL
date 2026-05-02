<?php

namespace App\Services;

use App\Models\Machine;
use App\Models\Tache;
use App\Models\Technicien;
use App\Models\Rapport;
use App\Models\Intervention;

/**
 * Service du tableau de bord admin.
 * Centralise le calcul de toutes les statistiques affichées.
 */
class DashboardService
{
    /**
     * Retourne toutes les statistiques consolidées pour le dashboard admin.
     *
     * @return array{
     *   machinesCount: int,
     *   machinesActives: int,
     *   machinesPanne: int,
     *   machinesMaintenance: int,
     *   tachesEnAttente: int,
     *   tachesEnCours: int,
     *   tachesTerminees: int,
     *   techniciensCount: int,
     *   techniciensDisponibles: int,
     *   rapportsCount: int,
     *   interventionsCount: int,
     *   recentesTaches: \Illuminate\Database\Eloquent\Collection,
     *   recentesInterventions: \Illuminate\Database\Eloquent\Collection,
     * }
     */
    public function getStatistiquesAdmin(): array
    {
        return [
            // Machines
            'machinesCount'       => Machine::count(),
            'machinesActives'     => Machine::where('etat', 'ACTIF')->count(),
            'machinesPanne'       => Machine::where('etat', 'PANNE')->count(),
            'machinesMaintenance' => Machine::where('etat', 'MAINTENANCE')->count(),

            // Tâches
            'tachesEnAttente' => Tache::where('statut', 'en attente')->count(),
            'tachesEnCours'   => Tache::where('statut', 'en cours')->count(),
            'tachesTerminees' => Tache::where('statut', 'terminé')->count(),

            // Techniciens
            'techniciensCount'      => Technicien::count(),
            'techniciensDisponibles'=> Technicien::where('disponible', true)->count(),
            'techniciensActifs'     => Technicien::where('disponible', true)->count(),

            // Rapports & Interventions
            'rapportsCount'      => Rapport::count(),
            'interventionsCount' => Intervention::count(),

            // Données récentes
            'recentesTaches' => Tache::with('technicien.user')
                ->latest()
                ->take(5)
                ->get(),

            'recentesInterventions' => Intervention::with(['machine', 'technicien.user'])
                ->latest()
                ->take(5)
                ->get(),
        ];
    }

    /**
     * Retourne les statistiques personnelles d'un technicien.
     */
    public function getStatistiquesTechnicien(int $technicienId): array
    {
        return [
            'mesEnAttente' => Tache::where('technicien_id', $technicienId)
                ->where('statut', 'en attente')->count(),
            'mesEnCours'   => Tache::where('technicien_id', $technicienId)
                ->where('statut', 'en cours')->count(),
            'mesTerminees' => Tache::where('technicien_id', $technicienId)
                ->where('statut', 'terminé')->count(),
            'mesInterventions' => Intervention::where('technicien_id', $technicienId)->count(),

            'mesTachesRecentes' => Tache::with('machine')
                ->where('technicien_id', $technicienId)
                ->latest()
                ->take(5)
                ->get(),
        ];
    }
}
