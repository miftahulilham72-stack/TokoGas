@extends('layouts.app')

@section('title', 'Edit Jenis Gas')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-edit"></i> Edit Jenis Gas</h1>
</div>

<div class="card">
    <div class="card-body">
        <form action="{{ route('jenis-gas.update', $jenisGas->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="nama" class="form-label">Nama Gas <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                       id="nama" name="nama" value="{{ old('nama', $jenisGas->nama) }}" required>
                @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="stok_minimum" class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('stok_minimum') is-invalid @enderror" 
                       id="stok_minimum" name="stok_minimum" value="{{ old('stok_minimum', $jenisGas->stok_minimum) }}" min="0" required>
                <div class="form-text">Jumlah stok minimal sebelum mendapatkan peringatan.</div>
                @error('stok_minimum')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update
                </button>
                <a href="{{ route('jenis-gas.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection