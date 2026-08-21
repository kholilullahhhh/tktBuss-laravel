<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class SeatRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'bus_id' => ['required', 'exists:buses,id'],
            'nomor_kursi' => ['required', 'string', 'max:10'],
            'posisi' => ['nullable', 'string', 'max:50'],
            'status' => ['required', Rule::in(['aktif', 'rusak'])],
        ];
    }

    public function messages(): array
    {
        return [
            'bus_id.required' => 'Bus wajib dipilih.',
            'nomor_kursi.required' => 'Nomor kursi wajib diisi.',
            'status.required' => 'Status kursi wajib dipilih.',
        ];
    }
}
