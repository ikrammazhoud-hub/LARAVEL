<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RapportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'machine_id' => 'required|exists:machines,id',
            'contenu' => 'required|string|min:10',
        ];
    }

    public function messages(): array
    {
        return [
            'machine_id.required' => 'La machine est obligatoire.',
            'machine_id.exists' => 'La machine n\'existe pas.',
            'contenu.required' => 'Le contenu du rapport est obligatoire.',
            'contenu.min' => 'Le contenu doit contenir au moins 10 caractères.',
        ];
    }
}
