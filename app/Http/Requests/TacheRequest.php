<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation pour la création/modification d'une Tâche.
 */
class TacheRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'technicien_id' => 'required|exists:techniciens,id',
            'machine_id'    => 'nullable|exists:machines,id',
            'titre'         => 'required|string|max:255',
            'description'   => 'nullable|string|max:5000',
            'priorite'      => 'required|in:basse,moyenne,haute',
            'statut'        => 'nullable|in:en attente,en cours,terminé',
            'date_deadline' => 'required|date|after_or_equal:today',
        ];
    }

    public function messages(): array
    {
        return [
            'technicien_id.required'   => 'Veuillez sélectionner un technicien.',
            'technicien_id.exists'     => 'Le technicien sélectionné est invalide.',
            'titre.required'           => 'Le titre de la tâche est obligatoire.',
            'priorite.required'        => 'La priorité est obligatoire.',
            'priorite.in'              => 'La priorité doit être : basse, moyenne ou haute.',
            'date_deadline.required'   => 'La date limite est obligatoire.',
            'statut.in'                => 'Le statut doit être : en attente, en cours ou terminé.',
            'date_deadline.after_or_equal' => 'La date limite ne peut pas être dans le passé.',
        ];
    }
}
