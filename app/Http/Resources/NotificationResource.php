<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Ressource API pour la transformation d'une Notification.
 */
class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'type'       => $this->type,
            'titre'      => $this->titre,
            'message'    => $this->message,
            'lu'         => $this->lu,
            'data'       => $this->data,
            'created_at' => $this->created_at->diffForHumans(),
            'created_at_raw' => $this->created_at->toDateTimeString(),
        ];
    }
}
