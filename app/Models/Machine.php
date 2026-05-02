<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Machine — représente une machine de l'atelier.
 *
 * @property int    $id
 * @property string $nom
 * @property string $etat        (ACTIF, PANNE, MAINTENANCE)
 * @property string $localisation
 * @property string|null $numero_serie
 * @property string|null $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Machine extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'etat',
        'localisation',
        'numero_serie',
        'description',
    ];

    /** États possibles d'une machine. */
    public const ETATS = ['ACTIF', 'PANNE', 'MAINTENANCE'];

    /* ------------------------------------------------------------------ */
    /*  Relations                                                           */
    /* ------------------------------------------------------------------ */

    public function interventions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Intervention::class);
    }

    /* ------------------------------------------------------------------ */
    /*  Scopes                                                              */
    /* ------------------------------------------------------------------ */

    public function scopeActives(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('etat', 'ACTIF');
    }

    public function scopeEnPanne(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('etat', 'PANNE');
    }

    public function scopeEnMaintenance(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('etat', 'MAINTENANCE');
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                             */
    /* ------------------------------------------------------------------ */

    /**
     * Retourne le libellé CSS Tailwind correspondant à l'état.
     */
    public function badgeClass(): string
    {
        return match ($this->etat) {
            'ACTIF'       => 'badge-success',
            'PANNE'       => 'badge-danger',
            'MAINTENANCE' => 'badge-warning',
            default       => 'badge-secondary',
        };
    }
}
