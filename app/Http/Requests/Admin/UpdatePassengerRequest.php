<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePassengerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'dni_passport' => 'required|string|max:20',
            'nationality' => 'required|string|max:20',
            'gender' => ['required', Rule::in(['male', 'female'])],
            'date_of_birth' => 'required|date|before:today',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'last_name.required' => 'Los apellidos son obligatorios.',
            'dni_passport.required' => 'El DNI o pasaporte es obligatorio.',
            'nationality.required' => 'La nacionalidad es obligatoria.',
            'gender.required' => 'El género es obligatorio.',
            'date_of_birth.required' => 'La fecha de nacimiento es obligatoria.',
            'date_of_birth.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
        ];
    }
}
