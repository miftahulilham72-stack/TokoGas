@extends('layouts.app')

@section('title', 'Bayar Piutang')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-money-bill"></i> Bayar Piutang</h1>
</div>

<div class="card">
    <div class="card-body">
        <div class="alert alert-info">
            <strong>Pelanggan:</strong> {{ $penjualan->nama_pelanggan ?? '-' }}<br>
            <strong>Total Hutang:</strong> Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}<br>
            <strong>Sisa Piutang:</strong> Rp {{ number_format($sisaPiutang, 0, ',', '.') }}
        </div>
        
        <form action="{{ route('piutang.proses-bayar', $penjualan->id) }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="jumlah_bayar" class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('jumlah_bayar') is-invalid @enderror" 
                       id="jumlah_bayar" name="jumlah_bayar" 
                       value="{{ old('jumlah_bayar', $sisaPiutang) }}" 
                       min="1" max="{{ $sisaPiutang }}" required>
                <div class="form-text">Maksimal: Rp {{ number_format($sisaPiutang, 0, ',', '.') }}</div>
                @error('jumlah_bayar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="tanggal_bayar" class="form-label">Tanggal Bayar <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('tanggal_bayar') is-invalid @enderror" 
                       id="tanggal_bayar" name="tanggal_bayar" 
                       value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                @error('tanggal_bayar')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-save"></i> Bayar
                </button>
                <a href="{{ route('piutang.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection