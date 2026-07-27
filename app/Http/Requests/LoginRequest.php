<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Override;

class LoginRequest extends FormRequest
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
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8']
        ];
    }

    #[Override]
    public function messages()
    {
        return [
            'email.required' => 'Email Wajib Diisi.',
            'email.email' => 'Format email tidak Valid.',
            'password.required' => 'Password Wajib Diisi.',
            'password.min' => 'Password minimal',
        ];
    }
}
