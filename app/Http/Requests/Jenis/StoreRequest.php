<?php

namespace App\Http\Requests\Jenis;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // authorize dicek manual di controller pakai $this->authorize()
    }

    public function rules(): array
    {
        return [
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_jenis.required' => 'Nama jenis wajib diisi.',
            'nama_jenis.string'   => 'Nama jenis harus berupa teks.',
            'nama_jenis.max'      => 'Nama jenis maksimal 255 karakter.',
            'nama_jenis.unique'   => 'Nama jenis sudah terdaftar, silakan gunakan nama lain.',
        ];
    }
}