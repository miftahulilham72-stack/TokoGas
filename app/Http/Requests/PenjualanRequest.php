<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PenjualanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tipe_pelanggan' => 'required|in:toko,rumahan',
            'tanggal_jual' => 'required|date',
            'status_pembayaran' => 'required|in:lunas,hutang',
            'nama_pelanggan' => 'required_if:status_pembayaran,hutang|nullable|string|max:255',
            'jatuh_tempo' => 'required_if:status_pembayaran,hutang|nullable|date|after_or_equal:tanggal_jual',
            'items' => 'required|array|min:1',
            'items.*.jenis_gas_id' => 'required|exists:jenis_gas,id',
            'items.*.jumlah' => 'required|integer|min:1',
            'items.*.harga_jual' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'tipe_pelanggan.required' => 'Tipe pelanggan harus dipilih',
            'tanggal_jual.required' => 'Tanggal jual harus diisi',
            'items.required' => 'Minimal 1 item harus diisi',
            'nama_pelanggan.required_if' => 'Nama pelanggan harus diisi jika hutang',
        ];
    }
}