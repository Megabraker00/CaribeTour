<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'itId' => 'required|exists:itineraries,id',
            'quantity' => 'required|integer|min:1|max:20',
            'customer_name' => 'required|string|max:100',
            'customer_last_name' => 'required|string|max:100',
            'customer_nationality' => 'required|string|max:20',
            'customer_document' => 'required|string|max:20',
            'customer_email' => 'required|email|max:150',
            'customer_phone' => 'required|string|max:30',
            'passengers' => 'required|array',
            'passengers.*.first_name' => 'required|string|max:100',
            'passengers.*.last_name' => 'required|string|max:100',
            'passengers.*.nationality' => 'required|string|max:20',
            'passengers.*.document' => 'required|string|max:20',
            'passengers.*.gender' => 'required|in:male,female',
            'passengers.*.birth_date' => 'required|date|before:today',
            'notes' => 'nullable|string|max:1000'
        ];
    }

    public function messages(): array
    {
        return [
            'passengers.*.birth_date.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'customer_email.email' => 'El formato del correo electrónico no es válido.',
        ];
    }

    // En app/Http/Requests/StoreReservationRequest.php

    public function attributes(): array
    {
        return [
            'customer_name' => 'nombre del titular',
            'passengers.*.first_name' => 'nombre del pasajero',
            'passengers.*.last_name' => 'apellido del pasajero',
            'passengers.*.birth_date' => 'fecha de nacimiento',
        ];
    }
}
