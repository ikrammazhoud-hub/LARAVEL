<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Tache — représente une tâche de maintenance assignée à un technicien.
 *
 * @property int    $id
 * @property int    $technicien_id
 * @property int|null $machine_id
 * @property string $titre
 * @property string|null $description
 * @property string $priorite   (basse, normale, haute, urgente)
 * @property string $statut     (en attente, en cours, terminé)
 * @property \Carbon\Carbon|null $date_deadline
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Tache extends Model
{
    use HasFactory;

    protected $fillable = [
        'technicien_id',
        'machine_id',
        'titre',
        'description',
        'priorite',
        'statut',
        'date_deadline',
    ];

    protected $casts = [
        'date_deadline' => 'date',
    ];

    /** Statuts possibles. */
    public const STATUTS = ['en attente', 'en cours', 'terminé'];

    /** Niveaux de priorité. */
    public const PRIORITES = ['basse', 'normale', 'haute', 'urgente'];

    /* ------------------------------------------------------------------ */
    /*  Relations                                                           */
    /* ------------------------------------------------------------------ */

    public function technicien(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Technicien::class);
    }

    public function machine(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function interventions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Intervention::class);
    }

    /* ------------------------------------------------------------------ */
    /*  Scopes                                                              */
    /* ------------------------------------------------------------------ */

    public function scopeEnCours(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('statut', 'en cours');
    }

    public function scopeEnAttente(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('statut', 'en attente');
    }

    public function scopeTerminees(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('statut', 'terminé');
    }

    /* ------------------------------------------------------------------ */
    /*  Helpers                                                             */
    /* ------------------------------------------------------------------ */

    /**
     * Vérifie si la date limite est dépassée.
     */
    public function estEnRetard(): bool
    {
        return $this->date_deadline && $this->date_deadline->isPast() && $this->statut !== 'terminé';
    }

    /**
     * Retourne la classe CSS de priorité.
     */
    public function prioriteBadgeClass(): string
    {
        return match ($this->priorite) {
            'urgente' => 'badge-danger',
            'haute'   => 'badge-warning',
            'normale' => 'badge-info',
            'basse'   => 'badge-secondary',
            default   => 'badge-secondary',
        };
    }
}
