<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($this->user->id),
            ],
            'password' => 'nullable|min:8',
            'role_id' => 'required',
            'is_active' => 'boolean'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama Wajib diisi.',
            'name.max' => 'Maksimal panjang nama 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.min' => 'Password minimal :min karakter.',
            'role.required' => 'Role wajib diisi.'
        ];
    }

}
