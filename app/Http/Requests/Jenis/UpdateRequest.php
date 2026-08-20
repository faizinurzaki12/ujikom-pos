<?php

namespace App\Http\Requests\Jenis;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Ambil id jenis yang sedang diedit dari route model binding,
        // supaya validasi unique tidak menolak nama yang sama dengan
        // dirinya sendiri.
        $jenisId = $this->route('jenis')->id;

        return [
            'nama_jenis' => 'required|string|max:255|unique:jenis,nama_jenis,' . $jenisId,
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