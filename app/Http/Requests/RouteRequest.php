<?php

namespace App\Http\Requests;

class RouteRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'terminal_asal_id' => ['required', 'exists:terminals,id', 'different:terminal_tujuan_id'],
            'terminal_tujuan_id' => ['required', 'exists:terminals,id'],
            'jarak' => ['nullable', 'numeric', 'min:0'],
            'estimasi_durasi' => ['nullable', 'integer', 'min:0'],
            'status' => ['sometimes', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'terminal_asal_id.required' => 'Terminal asal wajib dipilih.',
            'terminal_tujuan_id.required' => 'Terminal tujuan wajib dipilih.',
            'terminal_asal_id.different' => 'Terminal asal dan tujuan tidak boleh sama.',
        ];
    }
}
