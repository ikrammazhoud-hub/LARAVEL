<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TacheResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'priorite' => $this->priorite,
            'statut' => $this->statut,
            'date_deadline' => $this->date_deadline->format('Y-m-d'),
            'technicien' => [
                'id' => $this->technicien->id,
                'specialite' => $this->technicien->specialite,
                'user' => [
                    'name' => $this->technicien->user->name,
                ],
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
