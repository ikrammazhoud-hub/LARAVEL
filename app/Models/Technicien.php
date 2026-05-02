<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Technicien — profil d'un technicien de maintenance.
 *
 * @property int    $id
 * @property int    $user_id
 * @property string $specialite
 * @property string|null $telephone
 * @property string|null $matricule
 * @property bool   $disponible
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Technicien extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialite',
        'telephone',
        'matricule',
        'disponible',
    ];

    protected $casts = [
        'disponible' => 'boolean',
    ];

    /* ------------------------------------------------------------------ */
    /*  Relations                                                           */
    /* ------------------------------------------------------------------ */

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function taches(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Tache::class);
    }

    public function interventions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Intervention::class);
    }

    /* ------------------------------------------------------------------ */
    /*  Scopes                                                              */
    /* ------------------------------------------------------------------ */

    public function scopeDisponibles(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('disponible', true);
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                             */
    /* ------------------------------------------------------------------ */

    /**
     * Retourne le nom complet du technicien via la relation User.
     */
    public function nomComplet(): string
    {
        return $this->user?->name ?? 'Inconnu';
    }
}
