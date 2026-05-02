<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validation pour la création/modification d'un Technicien.
 */
class TechnicienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $technicienId = $this->route('technicien') instanceof \App\Models\Technicien
            ? $this->route('technicien')->id
            : $this->route('technicien');

        // Pour update, on récupère l'user_id du technicien
        $userId = null;
        if ($technicienId) {
            $userId = \App\Models\Technicien::find($technicienId)?->user_id;
        }

        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,' . $userId,
            'password'   => $isUpdate ? 'nullable|string|min:8' : 'required|string|min:8',
            'specialite' => 'required|string|max:255',
            'telephone'  => 'required|string|max:20',
            'matricule'  => 'nullable|string|max:50|unique:techniciens,matricule,' . $technicienId,
            'disponible' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'       => 'Le nom complet est obligatoire.',
            'email.required'      => "L'adresse email est obligatoire.",
            'email.unique'        => 'Cet email est déjà utilisé.',
            'password.required'   => 'Le mot de passe est obligatoire.',
            'password.min'        => 'Le mot de passe doit contenir au moins 8 caractères.',
            'specialite.required' => 'La spécialité est obligatoire.',
            'telephone.required'  => 'Le numéro de téléphone est obligatoire.',
            'matricule.unique'    => 'Ce matricule est déjà utilisé.',
        ];
    }
}
