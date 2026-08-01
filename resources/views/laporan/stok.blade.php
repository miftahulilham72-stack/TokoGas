@extends('layouts.app')

@section('title', 'Laporan Stok')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-boxes"></i> Laporan Stok</h1>
    <div>
        <a href="{{ route('laporan.cetak-stok-pdf') }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    @foreach($stokData as $item)
    <div class="col-md-3 mb-3">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $item->nama }}</h5>
                @php
                    $stok = $item->stok;
                    $currentStok = $stok ? $stok->jumlah_stok : 0;
                    $status = $currentStok < $item->stok_minimum ? 'danger' : 'success';
                    $label = $currentStok < $item->stok_minimum ? '⚠️ Menipis' : '✅ Aman';
                @endphp
                <h2 class="text-{{ $status }}">{{ $currentStok }}</h2>
                <p class="text-muted">Minimum: {{ $item->stok_minimum }}</p>
                <span class="badge bg-{{ $status }} badge-minimum">{{ $label }}</span>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if(count($rekomendasi) > 0)
<div class="card mt-3">
    <div class="card-header bg-warning">
        <h5><i class="fas fa-lightbulb"></i> Rekomendasi Pembelian</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Jenis Gas</th>
                        <th>Stok Saat Ini</th>
                        <th>Stok Minimum</th>
                        <th>Butuh</th>
                        <th>Perkiraan Harga</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rekomendasi as $item)
                    <tr>
                        <td>{{ $item['jenis_gas'] }}</td>
                        <td>{{ $item['stok_sekarang'] }}</td>
                        <td>{{ $item['stok_minimum'] }}</td>
                        <td>{{ $item['butuh'] }}</td>
                        <td>Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Total Modal Dibutuhkan</th>
                        <th class="text-danger">Rp {{ number_format($totalModal, 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endif
@endsection