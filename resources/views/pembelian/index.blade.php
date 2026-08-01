@extends('layouts.app')

@section('title', 'Data Pembelian')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-arrow-down"></i> Data Pembelian</h1>
    <a href="{{ route('pembelian.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Pembelian Baru
    </a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Agen</th>
                        <th>Detail</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pembelians as $pembelian)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $pembelian->tanggal_beli->format('d/m/Y') }}</td>
                        <td>{{ $pembelian->agen->nama }}</td>
                        <td>
                            @foreach($pembelian->details as $detail)
                                <span class="badge bg-info">{{ $detail->jenisGas->nama }}</span>
                                {{ $detail->jumlah_beli }} tabung<br>
                            @endforeach
                        </td>
                        <td>Rp {{ number_format($pembelian->total_harga, 0, ',', '.') }}</td>
                        <td>
                            <div class="table-actions">
                                <a href="{{ route('pembelian.show', $pembelian) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('pembelian.destroy', $pembelian) }}" method="POST" onsubmit="return confirm('Yakin hapus? Stok akan dikembalikan.')">
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
                        <td colspan="6" class="text-center">Belum ada data pembelian</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection