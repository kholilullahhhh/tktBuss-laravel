<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class BusRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'operator_id' => ['required', 'exists:operators,id'],
            'nomor_polisi' => ['required', 'string', 'max:20', Rule::unique('buses', 'nomor_polisi')->ignore($this->route('bus'))],
            'kode_bus' => ['required', 'string', 'max:50', Rule::unique('buses', 'kode_bus')->ignore($this->route('bus'))],
            'nama_bus' => ['required', 'string', 'max:255'],
            'kelas' => ['required', Rule::in(['Ekonomi', 'Bisnis', 'Executive', 'Sleeper'])],
            'kapasitas' => ['required', 'integer', 'min:1'],
            'fasilitas' => ['nullable', 'string'],
            'status' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'operator_id.required' => 'Operator wajib dipilih.',
            'nomor_polisi.required' => 'Nomor polisi wajib diisi.',
            'nomor_polisi.unique' => 'Nomor polisi sudah digunakan.',
            'kode_bus.required' => 'Kode bus wajib diisi.',
            'kode_bus.unique' => 'Kode bus sudah digunakan.',
            'nama_bus.required' => 'Nama bus wajib diisi.',
            'kelas.required' => 'Kelas bus wajib dipilih.',
            'kapasitas.required' => 'Kapasitas wajib diisi.',
            'kapasitas.min' => 'Kapasitas minimal 1.',
        ];
    }
}
