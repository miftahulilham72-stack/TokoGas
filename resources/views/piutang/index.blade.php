@extends('layouts.app')

@section('title', 'Data Piutang')

@section('content')
<div class="page-header">
    <h1><i class="fas fa-hand-holding-usd"></i> Data Piutang</h1>
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
                        <th>Aksi</th>
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
                        <td>
                            <a href="{{ route('piutang.bayar', $piutang->id) }}" class="btn btn-sm btn-success">
                                <i class="fas fa-money-bill"></i> Bayar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada piutang</td>
                    </tr>
                    @endforelse
                </tbody>
                @if(isset($totalPiutang) && $totalPiutang > 0)
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total Piutang</th>
                        <th class="text-danger">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</th>
                        <th colspan="2"></th>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection