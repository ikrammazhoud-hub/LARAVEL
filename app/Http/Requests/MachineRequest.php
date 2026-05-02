<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation pour la création/modification d'une Machine.
 */
class MachineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $machineId = $this->route('machine') instanceof \App\Models\Machine
            ? $this->route('machine')->id
            : $this->route('machine');

        return [
            'nom'          => 'required|string|max:255',
            'etat'         => 'required|in:ACTIF,PANNE,MAINTENANCE',
            'localisation' => 'required|string|max:255',
            'numero_serie' => 'nullable|string|max:100|unique:machines,numero_serie,' . $machineId,
            'description'  => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required'          => 'Le nom de la machine est obligatoire.',
            'etat.required'         => "L'état de la machine est obligatoire.",
            'etat.in'               => "L'état doit être ACTIF, PANNE ou MAINTENANCE.",
            'localisation.required' => 'La localisation est obligatoire.',
            'numero_serie.unique'   => 'Ce numéro de série est déjà utilisé.',
        ];
    }
}
