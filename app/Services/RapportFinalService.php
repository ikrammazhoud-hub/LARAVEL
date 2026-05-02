<?php

namespace App\Services;

use App\Models\Intervention;
use App\Models\Rapport;
use App\Models\Tache;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class RapportFinalService
{
    public function demarrerTache(Tache $tache): void
    {
        $tache->update(['statut' => 'en cours']);

        if (! $tache->machine_id) {
            return;
        }

        Intervention::firstOrCreate(
            [
                'tache_id' => $tache->id,
                'date_fin' => null,
            ],
            [
                'machine_id' => $tache->machine_id,
                'technicien_id' => $tache->technicien_id,
                'description' => "Intervention ouverte automatiquement pour la tâche « {$tache->titre} ».",
                'statut' => 'en_cours',
                'date_debut' => now(),
            ]
        );
    }

    public function finaliserTache(Tache $tache, ?string $contenu = null, ?int $machineId = null): Rapport
    {
        $tache->loadMissing(['machine', 'technicien.user']);

        $intervention = $this->resolveIntervention($tache, $contenu, $machineId);
        $rapport = $this->resolveRapport($intervention, $tache, $contenu);

        $tache->update(['statut' => 'terminé']);

        return $this->genererPdf($rapport);
    }

    public function genererPdf(Rapport $rapport): Rapport
    {
        $rapport->load(['intervention.machine', 'intervention.technicien.user', 'intervention.tache']);

        $path = 'rapports/rapport_tache_' . ($rapport->intervention->tache_id ?? 'intervention_' . $rapport->intervention_id) . '.pdf';
        $pdf = Pdf::loadView('admin.rapport_pdf', compact('rapport'))->output();

        Storage::disk('public')->put($path, $pdf);

        $rapport->update([
            'pdf_path' => $path,
            'pdf_generated_at' => now(),
        ]);

        return $rapport->refresh();
    }

    private function resolveIntervention(Tache $tache, ?string $contenu, ?int $machineId): Intervention
    {
        $intervention = Intervention::where('tache_id', $tache->id)
            ->latest()
            ->first();

        $resolvedMachineId = $machineId
            ?? $tache->machine_id
            ?? $intervention?->machine_id;

        if (! $resolvedMachineId) {
            throw ValidationException::withMessages([
                'machine_id' => 'Veuillez sélectionner une machine avant de clôturer la tâche et générer le rapport final.',
            ]);
        }

        if (! $intervention) {
            $intervention = Intervention::create([
                'machine_id' => $resolvedMachineId,
                'technicien_id' => $tache->technicien_id,
                'tache_id' => $tache->id,
                'description' => $contenu ?: "Rapport final généré automatiquement pour la tâche « {$tache->titre} ».",
                'statut' => 'terminee',
                'date_debut' => $tache->created_at,
                'date_fin' => now(),
            ]);
        } else {
            $intervention->update([
                'machine_id' => $intervention->machine_id ?: $resolvedMachineId,
                'description' => $contenu ?: $intervention->description,
                'statut' => 'terminee',
                'date_debut' => $intervention->date_debut ?: $tache->created_at,
                'date_fin' => now(),
            ]);
        }

        return $intervention->refresh();
    }

    private function resolveRapport(Intervention $intervention, Tache $tache, ?string $contenu): Rapport
    {
        $rapport = $intervention->rapport;

        $rapportContenu = $contenu ?: $rapport?->contenu ?: implode("\n", [
            "Rapport final généré automatiquement.",
            "Tâche : {$tache->titre}",
            "Statut : terminé",
            "Suivi : la tâche a été clôturée et archivée avec un rapport PDF.",
        ]);

        if ($rapport) {
            $rapport->update(['contenu' => $rapportContenu]);

            return $rapport->refresh();
        }

        return Rapport::create([
            'intervention_id' => $intervention->id,
            'contenu' => $rapportContenu,
            'observations' => 'Rapport final associé automatiquement à la clôture de la tâche.',
            'recommandations' => 'Vérifier les indicateurs de suivi depuis le dashboard administrateur.',
        ]);
    }
}
