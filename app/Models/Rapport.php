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
    ];

    /* ------------------------------------------------------------------ */
    /*  Relations                                                           */
    /* ------------------------------------------------------------------ */

    public function intervention(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Intervention::class);
    }
}
