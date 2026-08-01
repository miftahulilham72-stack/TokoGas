@extends('layouts.app')

@section('title', 'Detail Pembelian')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-eye"></i> Detail Pembelian</h1>
    <a href="{{ route('pembelian.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5>Faktur: {{ $pembelian->no_faktur ?? '-' }}</h5>
    </div>
    <div class="card-body">
        <div class="row mb-3">
            <div class="col-md-6">
                <strong>Agen:</strong> {{ $pembelian->agen->nama }}
            </div>
            <div class="col-md-6">
                <strong>Tanggal:</strong> {{ $pembelian->tanggal_beli->format('d/m/Y') }}
            </div>
        </div>
        
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Jenis Gas</th>
                        <th>Jumlah</th>
                        <th>Harga Beli</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembelian->details as $detail)
                    <tr>
                        <td>{{ $detail->jenisGas->nama }}</td>
                        <td>{{ $detail->jumlah_beli }}</td>
                        <td>Rp {{ number_format($detail->harga_beli_saat_itu, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($detail->jumlah_beli * $detail->harga_beli_saat_itu, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Total</th>
                        <th>Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection