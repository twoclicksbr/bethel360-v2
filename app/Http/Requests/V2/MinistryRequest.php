<?php

namespace App\Http\Requests\V2;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MinistryRequest extends FormRequest
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
        $ministryId = $this->route('ministry');

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('ministries')->ignore($ministryId),
            ],
            'campus_id' => ['required', 'exists:campuses,id'],
            'description' => ['nullable', 'string'],
            'is_active' => ['boolean'],
            'is_confidential' => ['boolean'],
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
            'name.required' => 'O nome do ministério é obrigatório',
            'slug.required' => 'O slug do ministério é obrigatório',
            'slug.regex' => 'O slug deve conter apenas letras minúsculas, números e hífens',
            'slug.unique' => 'Este slug já está em uso',
            'campus_id.required' => 'O campus é obrigatório',
            'campus_id.exists' => 'Campus não encontrado',
        ];
    }
}
