<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PiutangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jumlah_bayar' => 'required|numeric|min:1',
            'tanggal_bayar' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'jumlah_bayar.required' => 'Jumlah bayar harus diisi',
            'jumlah_bayar.min' => 'Jumlah bayar minimal 1',
            'tanggal_bayar.required' => 'Tanggal bayar harus diisi',
        ];
    }
}