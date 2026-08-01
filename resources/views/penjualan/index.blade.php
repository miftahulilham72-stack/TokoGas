@extends('layouts.app')

@section('title', 'Data Penjualan')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-arrow-up"></i> Data Penjualan</h1>
    <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Penjualan Baru
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
                        <th>Tipe</th>
                        <th>Detail</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($penjualans as $penjualan)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $penjualan->tanggal_jual->format('d/m/Y') }}</td>
                        <td>
                            <span class="badge bg-{{ $penjualan->tipe_pelanggan == 'toko' ? 'info' : 'success' }}">
                                {{ ucfirst($penjualan->tipe_pelanggan) }}
                            </span>
                        </td>
                        <td>
                            @foreach($penjualan->details as $detail)
                                <span class="badge bg-secondary">{{ $detail->jenisGas->nama }}</span>
                                {{ $detail->jumlah_jual }} tabung<br>
                            @endforeach
                        </td>
                        <td>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</td>
                        <td>
                            @if($penjualan->status_pembayaran == 'lunas')
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-danger">Hutang</span>
                                <br>
                                <small>Sisa: Rp {{ number_format($penjualan->sisa_piutang, 0, ',', '.') }}</small>
                            @endif
                        </td>
                        <td>
                            <div class="table-actions">
                                @if($penjualan->status_pembayaran == 'hutang' && $penjualan->sisa_piutang > 0)
                                    <a href="{{ route('piutang.bayar', $penjualan->id) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-money-bill"></i> Bayar
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Belum ada data penjualan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection