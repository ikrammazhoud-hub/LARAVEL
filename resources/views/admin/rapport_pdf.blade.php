<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rapport d'Intervention</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5; }
        .header { text-align: center; border-bottom: 3px solid #4f46e5; padding-bottom: 20px; margin-bottom: 30px; }
        .title { font-size: 24px; font-weight: bold; color: #1e1b4b; text-transform: uppercase; letter-spacing: 1px; }
        .subtitle { color: #6b7280; font-size: 12px; margin-top: 5px; }
        .details { margin-bottom: 30px; }
        .details table { width: 100%; border-collapse: collapse; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .details th, .details td { padding: 12px; border: 1px solid #e5e7eb; text-align: left; }
        .details th { background-color: #f3f4f6; width: 35%; color: #374151; font-weight: bold; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; }
        .content-box { margin-top: 20px; padding: 20px; border: 1px solid #e5e7eb; background-color: #ffffff; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
        .content-title { color: #1e1b4b; font-size: 16px; border-bottom: 2px solid #e5e7eb; padding-bottom: 10px; margin-bottom: 15px; }
        .footer { text-align: center; margin-top: 50px; font-size: 10px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 15px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Rapport Officiel d'Intervention</div>
        <div class="subtitle">Document généré le {{ now()->format('d/m/Y à H:i') }} | ID Référence : #{{ str_pad($rapport->id, 5, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="details">
        <table>
            <tr>
                <th>Tâche Associée</th>
                <td><strong>{{ $rapport->intervention->tache->titre ?? 'Intervention indépendante' }}</strong></td>
            </tr>
            <tr>
                <th>Machine Ciblée</th>
                <td><strong>{{ $rapport->intervention->machine->nom ?? 'Inconnue' }}</strong></td>
            </tr>
            <tr>
                <th>Technicien Responsable</th>
                <td>{{ $rapport->intervention->technicien->user->name ?? 'Inconnu' }} (Spécialité: {{ $rapport->intervention->technicien->specialite ?? 'N/A' }})</td>
            </tr>
            <tr>
                <th>Date / Heure de Début</th>
                <td>{{ $rapport->intervention->date_debut ? $rapport->intervention->date_debut->format('d/m/Y H:i') : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Date / Heure de Fin</th>
                <td>{{ $rapport->intervention->date_fin ? $rapport->intervention->date_fin->format('d/m/Y H:i') : 'N/A' }}</td>
            </tr>
            <tr>
                <th>Statut du Rapport</th>
                <td>Rapport final PDF généré automatiquement{{ $rapport->pdf_generated_at ? ' le ' . $rapport->pdf_generated_at->format('d/m/Y H:i') : '' }}</td>
            </tr>
        </table>
    </div>

    <div class="content-box">
        <div class="content-title">Détail des opérations réalisées</div>
        <p style="white-space: pre-line; color: #4b5563;">
            {{ $rapport->contenu }}
        </p>
    </div>

    @if($rapport->observations || $rapport->pieces_changees || $rapport->recommandations)
    <div class="content-box">
        <div class="content-title">Suivi complémentaire</div>
        @if($rapport->observations)
            <p><strong>Observations :</strong> {{ $rapport->observations }}</p>
        @endif
        @if($rapport->pieces_changees)
            <p><strong>Pièces changées :</strong> {{ $rapport->pieces_changees }}</p>
        @endif
        @if($rapport->recommandations)
            <p><strong>Recommandations :</strong> {{ $rapport->recommandations }}</p>
        @endif
    </div>
    @endif

    <div class="footer">
        Gestion d'Atelier Industriel - Généré automatiquement par la plateforme.
    </div>
</body>
</html>
