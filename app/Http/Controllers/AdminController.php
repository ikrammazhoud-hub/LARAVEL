<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\RapportFinalService;
use App\Models\Rapport;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Contrôleur principal de l'espace administration.
 */
class AdminController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(
        DashboardService $dashboardService,
        private RapportFinalService $rapportFinalService
    )
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Affiche le tableau de bord admin avec toutes les statistiques.
     */
    public function dashboard(): View
    {
        $stats = $this->dashboardService->getStatistiquesAdmin();

        return view('admin.dashboard', $stats);
    }

    /**
     * Exporte un rapport d'intervention au format PDF.
     */
    public function exportPdf(Rapport $rapport): \Symfony\Component\HttpFoundation\Response
    {
        $rapport->load(['intervention.machine', 'intervention.technicien.user', 'intervention.tache']);

        if (! $rapport->pdf_path || ! Storage::disk('public')->exists($rapport->pdf_path)) {
            $rapport = $this->rapportFinalService->genererPdf($rapport);
        }

        return Storage::disk('public')->download(
            $rapport->pdf_path,
            'rapport_final_tache_' . ($rapport->intervention->tache_id ?? $rapport->id) . '.pdf'
        );
    }
}
