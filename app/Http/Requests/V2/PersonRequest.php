<?php

namespace App\Http\Requests\V2;

use Illuminate\Foundation\Http\FormRequest;

class PersonRequest extends FormRequest
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
            'first_name' => ['required_without:name', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'name' => ['required_without:first_name', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'gender_id' => ['nullable', 'exists:genders,id'],
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
            'first_name.required_without' => 'O nome é obrigatório',
            'name.required_without' => 'O nome completo é obrigatório',
            'birth_date.date' => 'Data de nascimento inválida',
            'birth_date.before' => 'Data de nascimento deve ser anterior a hoje',
            'gender_id.exists' => 'Gênero não encontrado',
        ];
    }
}
