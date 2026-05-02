<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation des données pour la création/modification d'une Intervention.
 */
class InterventionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'machine_id'    => 'required|exists:machines,id',
            'technicien_id' => 'required|exists:techniciens,id',
            'tache_id'      => 'nullable|exists:taches,id',
            'description'   => 'required|string|min:10',
            'statut'        => 'required|in:en_cours,terminee',
            'date_debut'    => 'nullable|date',
            'date_fin'      => 'nullable|date|after_or_equal:date_debut',
        ];
    }

    public function messages(): array
    {
        return [
            'machine_id.required'    => 'Veuillez sélectionner une machine.',
            'machine_id.exists'      => 'La machine sélectionnée est invalide.',
            'technicien_id.required' => 'Veuillez sélectionner un technicien.',
            'technicien_id.exists'   => 'Le technicien sélectionné est invalide.',
            'description.required'   => 'La description de l\'intervention est obligatoire.',
            'description.min'        => 'La description doit comporter au moins 10 caractères.',
            'statut.required'        => 'Le statut est obligatoire.',
            'statut.in'              => 'Le statut doit être "en_cours" ou "terminee".',
            'date_fin.after_or_equal'=> 'La date de fin doit être postérieure ou égale à la date de début.',
        ];
    }
}
