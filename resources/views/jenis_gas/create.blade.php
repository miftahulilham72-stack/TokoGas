@extends('layouts.app')

@section('title', 'Tambah Jenis Gas')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-plus"></i> Tambah Jenis Gas</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('jenis-gas.store') }}" method="POST">
            @csrf
            
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Gas <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                       id="nama" name="nama" value="{{ old('nama') }}" 
                       placeholder="Contoh: 3kg, 5kg (Blue Gas), 5.5kg (Pink Gas), 12kg" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="stok_minimum" class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('stok_minimum') is-invalid @enderror" 
                       id="stok_minimum" name="stok_minimum" value="{{ old('stok_minimum', 2) }}" min="0" required>
                <div class="form-text">Jumlah stok minimal sebelum mendapatkan peringatan.</div>
                @error('stok_minimum')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan
                </button>
                <a href="{{ route('jenis-gas.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection