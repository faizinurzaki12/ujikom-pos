<?php

namespace App\Http\Requests\User;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class StoreRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role_id' => 'required|exists:roles,id'
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'     => 'Nama Wajib diisi.',
            'name.max'     => 'Maksimal panjang nama 100 karakter.',
            'email.required'     => 'Email wajib diisi.',
            'email.email'     => 'Format email tidak valid.',
            'password.required'     => 'Password Wajib diisi.',
            'password.min'     => 'Password minimal :min karakter.',
            'role_id.required'     => 'Roles wajib diisi.',
        ];
    }
}
