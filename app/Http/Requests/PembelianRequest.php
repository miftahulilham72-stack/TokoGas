<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PembelianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'agen_id' => 'required|exists:agens,id',
            'tanggal_beli' => 'required|date',
            'no_faktur' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.jenis_gas_id' => 'required|exists:jenis_gas,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_beli' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'agen_id.required' => 'Agen harus dipilih',
            'tanggal_beli.required' => 'Tanggal beli harus diisi',
            'items.required' => 'Minimal 1 item harus diisi',
            'items.*.jumlah.min' => 'Jumlah minimal 1',
        ];
    }
}