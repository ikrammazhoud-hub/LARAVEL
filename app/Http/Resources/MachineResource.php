<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MachineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'etat' => $this->etat,
            'localisation' => $this->localisation,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'interventions_count' => $this->interventions()->count(),
        ];
    }
}
