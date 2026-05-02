<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant une notification interne.
 *
 * @property int    $id
 * @property int    $user_id
 * @property string $type     (tache_assignee, statut_change, alerte_machine)
 * @property string $titre
 * @property string $message
 * @property bool   $lu
 * @property array|null $data
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'titre',
        'message',
        'lu',
        'data',
    ];

    protected $casts = [
        'lu'   => 'boolean',
        'data' => 'array',
    ];

    /* ------------------------------------------------------------------ */
    /*  Relations                                                           */
    /* ------------------------------------------------------------------ */

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* ------------------------------------------------------------------ */
    /*  Scopes                                                              */
    /* ------------------------------------------------------------------ */

    /**
     * Filtre les notifications non lues.
     */
    public function scopeNonLues(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('lu', false);
    }

    /**
     * Filtre par utilisateur.
     */
    public function scopePourUtilisateur(
        \Illuminate\Database\Eloquent\Builder $query,
        int $userId
    ): \Illuminate\Database\Eloquent\Builder {
        return $query->where('user_id', $userId);
    }
}
