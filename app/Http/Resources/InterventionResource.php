<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ressource API pour la transformation d'une Intervention.
 */
class InterventionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'statut'      => $this->statut,
            'description' => $this->description,
            'date_debut'  => $this->date_debut?->toDateTimeString(),
            'date_fin'    => $this->date_fin?->toDateTimeString(),
            'machine'     => [
                'id'  => $this->machine?->id,
                'nom' => $this->machine?->nom,
                'etat'=> $this->machine?->etat,
            ],
            'technicien' => [
                'id'   => $this->technicien?->id,
                'nom'  => $this->technicien?->user?->name,
                'specialite' => $this->technicien?->specialite,
            ],
            'tache'  => $this->whenLoaded('tache', fn () => [
                'id'     => $this->tache->id,
                'titre'  => $this->tache->titre,
                'statut' => $this->tache->statut,
            ]),
            'rapport' => $this->whenLoaded('rapport', fn () => [
                'id'      => $this->rapport->id,
                'contenu' => $this->rapport->contenu,
            ]),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
