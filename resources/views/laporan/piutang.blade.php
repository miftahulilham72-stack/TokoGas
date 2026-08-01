@extends('layouts.app')

@section('title', 'Laporan Piutang')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-file-invoice"></i> Laporan Piutang</h1>
    <a href="{{ route('dashboard') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Kembali
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
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Sisa Piutang</th>
                        <th>Jatuh Tempo</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($piutangs as $piutang)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $piutang->tanggal_jual->format('d/m/Y') }}</td>
                        <td>{{ $piutang->nama_pelanggan ?? '-' }}</td>
                        <td>Rp {{ number_format($piutang->total_harga, 0, ',', '.') }}</td>
                        <td class="text-danger fw-bold">
                            Rp {{ number_format($piutang->sisa_piutang, 0, ',', '.') }}
                        </td>
                        <td>
                            @if($piutang->jatuh_tempo)
                                @if($piutang->jatuh_tempo < now())
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge bg-warning">{{ $piutang->jatuh_tempo->diffForHumans() }}</span>
                                @endif
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada piutang</td>
                    </tr>
                    @endforelse
                </tbody>
                @if(isset($totalPiutang) && $totalPiutang > 0)
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total Piutang</th>
                        <th class="text-danger">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</th>
                        <th></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection