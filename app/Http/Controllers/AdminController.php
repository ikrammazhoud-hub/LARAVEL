<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Models\Rapport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Contrôleur principal de l'espace administration.
 */
class AdminController extends Controller
{
    protected DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
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
        $rapport->load(['intervention.machine', 'intervention.technicien.user']);

        $pdf = Pdf::loadView('admin.rapport_pdf', compact('rapport'));

        return $pdf->download('rapport_intervention_' . $rapport->id . '.pdf');
    }
}
