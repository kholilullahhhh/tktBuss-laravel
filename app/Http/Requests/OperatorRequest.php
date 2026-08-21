<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class OperatorRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'nama_operator' => ['required', 'string', 'max:255'],
            'kode_operator' => ['required', 'string', 'max:50', Rule::unique('operators', 'kode_operator')->ignore($this->route('operator'))],
            'alamat' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:150'],
            'status' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_operator.required' => 'Nama operator wajib diisi.',
            'kode_operator.required' => 'Kode operator wajib diisi.',
            'kode_operator.unique' => 'Kode operator sudah digunakan.',
        ];
    }
}
