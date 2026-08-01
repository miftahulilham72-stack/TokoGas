@extends('layouts.app')

@section('title', 'Data Jenis Gas')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-fire"></i> Data Jenis Gas</h1>
    <a href="{{ route('jenis-gas.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Jenis Gas
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Gas</th>
                        <th>Stok Minimum</th>
                        <th>Stok Saat Ini</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenisGases as $gas)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $gas->nama }}</td>
                        <td>{{ $gas->stok_minimum }}</td>
                        <td>
                            @if($gas->stok)
                                {{ $gas->stok->jumlah_stok }}
                            @else
                                0
                            @endif
                        </td>
                        <td>
                            @php
                                $stok = $gas->stok;
                                $status = $stok ? ($stok->isMenipis() ? 'danger' : 'success') : 'secondary';
                                $label = $stok ? ($stok->isMenipis() ? '⚠️ Menipis' : '✅ Aman') : 'Belum ada stok';
                            @endphp
                            <span class="badge bg-{{ $status }} badge-minimum">{{ $label }}</span>
                        </td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('jenis-gas.edit', $gas->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('jenis-gas.destroy', $gas->id) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Belum ada data jenis gas</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection