<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RapportResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'contenu' => $this->contenu,
            'intervention' => [
                'id' => $this->intervention->id,
                'machine' => [
                    'id' => $this->intervention->machine->id,
                    'nom' => $this->intervention->machine->nom,
                    'etat' => $this->intervention->machine->etat,
                ],
                'technicien' => [
                    'id' => $this->intervention->technicien->id,
                    'specialite' => $this->intervention->technicien->specialite,
                    'user' => [
                        'name' => $this->intervention->technicien->user->name,
                    ],
                ],
                'date_debut' => $this->intervention->date_debut->format('Y-m-d H:i:s'),
                'date_fin' => $this->intervention->date_fin->format('Y-m-d H:i:s'),
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
