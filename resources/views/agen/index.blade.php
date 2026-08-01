@extends('layouts.app')

@section('title', 'Data Agen')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-truck"></i> Data Agen</h1>
    <a href="{{ route('agen.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Tambah Agen
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Agen</th>
                        <th>No HP</th>
                        <th>Alamat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($agens as $agen)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $agen->nama }}</td>
                        <td>{{ $agen->no_hp ?? '-' }}</td>
                        <td>{{ $agen->alamat ?? '-' }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('agen.edit', $agen) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('agen.destroy', $agen) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
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
                        <td colspan="5" class="text-center">Belum ada data agen</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection