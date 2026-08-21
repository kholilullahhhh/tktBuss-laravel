<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class TerminalRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'nama_terminal' => ['required', 'string', 'max:255'],
            'kode_terminal' => ['required', 'string', 'max:50', Rule::unique('terminals', 'kode_terminal')->ignore($this->route('terminal'))],
            'alamat' => ['nullable', 'string'],
            'kota' => ['nullable', 'string', 'max:100'],
            'provinsi' => ['nullable', 'string', 'max:100'],
            'status' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_terminal.required' => 'Nama terminal wajib diisi.',
            'kode_terminal.required' => 'Kode terminal wajib diisi.',
            'kode_terminal.unique' => 'Kode terminal sudah digunakan.',
        ];
    }
}
