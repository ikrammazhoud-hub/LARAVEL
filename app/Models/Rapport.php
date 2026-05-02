<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Rapport — rapport d'intervention généré par un technicien.
 *
 * @property int    $id
 * @property int    $intervention_id
 * @property string $contenu
 * @property string|null $observations
 * @property string|null $pieces_changees
 * @property string|null $recommandations
 * @property string|null $pdf_path
 * @property \Carbon\Carbon|null $pdf_generated_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Rapport extends Model
{
    use HasFactory;

    protected $fillable = [
        'intervention_id',
        'contenu',
        'observations',
        'pieces_changees',
        'recommandations',
        'pdf_path',
        'pdf_generated_at',
    ];

    protected $casts = [
        'pdf_generated_at' => 'datetime',
    ];

    /* ------------------------------------------------------------------ */
    /*  Relations                                                           */
    /* ------------------------------------------------------------------ */

    public function intervention(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }
}
