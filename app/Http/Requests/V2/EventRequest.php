<?php

namespace App\Http\Requests\V2;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Authorization is handled by policies
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'eventable_type' => ['required', 'string'],
            'eventable_id' => ['required', 'integer'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome do evento é obrigatório',
            'start_date.required' => 'A data de início é obrigatória',
            'start_date.date' => 'Data de início inválida',
            'end_date.required' => 'A data de término é obrigatória',
            'end_date.date' => 'Data de término inválida',
            'end_date.after_or_equal' => 'A data de término deve ser igual ou posterior à data de início',
            'eventable_type.required' => 'O tipo de entidade do evento é obrigatório',
            'eventable_id.required' => 'O ID da entidade do evento é obrigatório',
        ];
    }
}
