<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class ScheduleRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'bus_id' => ['required', 'exists:buses,id'],
            'route_id' => ['required', 'exists:routes,id'],
            'tanggal_keberangkatan' => ['required', 'date', 'after_or_equal:today'],
            'jam_keberangkatan' => ['required'],
            'jam_tiba' => ['required'],
            'harga' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['aktif', 'penuh', 'selesai', 'dibatalkan'])],
        ];
    }

    public function messages(): array
    {
        return [
            'bus_id.required' => 'Bus wajib dipilih.',
            'route_id.required' => 'Rute wajib dipilih.',
            'tanggal_keberangkatan.required' => 'Tanggal keberangkatan wajib diisi.',
            'tanggal_keberangkatan.after_or_equal' => 'Tanggal keberangkatan tidak boleh sebelum hari ini.',
            'jam_keberangkatan.required' => 'Jam keberangkatan wajib diisi.',
            'jam_tiba.required' => 'Jam tiba wajib diisi.',
            'harga.required' => 'Harga tiket wajib diisi.',
            'harga.min' => 'Harga tidak boleh negatif.',
            'status.required' => 'Status jadwal wajib dipilih.',
        ];
    }
}
