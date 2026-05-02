<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle représentant une intervention réalisée sur une machine.
 *
 * @property int         $id
 * @property int         $machine_id
 * @property int         $technicien_id
 * @property int|null    $tache_id
 * @property string      $description
 * @property string      $statut       (en_cours, terminee)
 * @property \Carbon\Carbon|null $date_debut
 * @property \Carbon\Carbon|null $date_fin
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Intervention extends Model
{
    use HasFactory;

    protected $fillable = [
        'machine_id',
        'technicien_id',
        'tache_id',
        'description',
        'statut',
        'date_debut',
        'date_fin',
    ];

    protected $casts = [
        'date_debut' => 'datetime',
        'date_fin'   => 'datetime',
    ];

    /* ------------------------------------------------------------------ */
    /*  Relations                                                           */
    /* ------------------------------------------------------------------ */

    public function machine(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Machine::class);
    }

    public function technicien(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Technicien::class);
    }

    public function tache(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tache::class);
    }

    public function rapport(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Rapport::class);
    }
}
