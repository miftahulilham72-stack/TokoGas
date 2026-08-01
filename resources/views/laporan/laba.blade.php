@extends('layouts.app')

@section('title', 'Laporan Laba / Rugi')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <h1><i class="fas fa-chart-line"></i> Laporan Laba / Rugi</h1>
    <div>
        <a href="{{ route('laporan.cetak-laba-pdf', ['bulan' => $bulan ?? date('m'), 'tahun' => $tahun ?? date('Y')]) }}" 
           class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<!-- Filter -->
<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('laporan.laba') }}" method="GET" class="row g-2 g-md-3">
            <div class="col-6 col-md-3">
                <label for="bulan" class="form-label">Bulan</label>
                <select name="bulan" id="bulan" class="form-select">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ ($bulan ?? date('m')) == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0,0,0,$i,1)) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label for="tahun" class="form-label">Tahun</label>
                <select name="tahun" id="tahun" class="form-select">
                    @for($i = date('Y'); $i >= date('Y')-5; $i--)
                        <option value="{{ $i }}" {{ ($tahun ?? date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-8 col-md-3">
                <label for="tanggal" class="form-label">Tanggal (opsional)</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" 
                       value="{{ $tanggal ?? '' }}">
            </div>
            <div class="col-4 col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan Total -->
<div class="row g-2 g-md-3 mb-3">
    <div class="col-6 col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body text-center p-2 p-md-3">
                <small>Total Penjualan</small>
                <h4 class="mb-0">
                    Rp {{ number_format(collect($labaPerHari)->sum('total_penjualan'), 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body text-center p-2 p-md-3">
                <small>Total Pembelian</small>
                <h4 class="mb-0">
                    Rp {{ number_format(collect($labaPerHari)->sum('total_pembelian'), 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card bg-warning text-dark">
            <div class="card-body text-center p-2 p-md-3">
                <small>Total Keuntungan</small>
                <h4 class="mb-0 fw-bold">
                    Rp {{ number_format($totalKeuntungan ?? 0, 0, ',', '.') }}
                </h4>
            </div>
        </div>
    </div>
</div>

<!-- Laporan per Hari -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-calendar-day"></i> 
            Laba per Hari
            @if($tanggal)
                <span class="badge bg-primary">Tanggal: {{ Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</span>
            @else
                <span class="badge bg-info">Bulan: {{ date('F', mktime(0,0,0,$bulan,1)) }} {{ $tahun }}</span>
            @endif
        </h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive-custom">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th class="text-end">Total Penjualan</th>
                        <th class="text-end">Total Pembelian</th>
                        <th class="text-end">Keuntungan</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($labaPerHari as $data)
                    <tr>
                        <td>
                            <strong>{{ $data['tanggal']->format('d/m/Y') }}</strong>
                            <br>
                            <small class="text-muted">{{ $data['tanggal']->format('l') }}</small>
                        </td>
                        <td class="text-end text-success">
                            Rp {{ number_format($data['total_penjualan'], 0, ',', '.') }}
                        </td>
                        <td class="text-end text-danger">
                            Rp {{ number_format($data['total_pembelian'], 0, ',', '.') }}
                        </td>
                        <td class="text-end fw-bold {{ $data['keuntungan'] >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($data['keuntungan'], 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($data['keuntungan'] > 0)
                                <span class="badge bg-success">Laba</span>
                            @elseif($data['keuntungan'] < 0)
                                <span class="badge bg-danger">Rugi</span>
                            @else
                                <span class="badge bg-secondary">Impas</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted"></i>
                            <p class="text-muted mt-2">Belum ada data penjualan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="table-active fw-bold">
                        <td>TOTAL</td>
                        <td class="text-end text-success">
                            Rp {{ number_format(collect($labaPerHari)->sum('total_penjualan'), 0, ',', '.') }}
                        </td>
                        <td class="text-end text-danger">
                            Rp {{ number_format(collect($labaPerHari)->sum('total_pembelian'), 0, ',', '.') }}
                        </td>
                        <td class="text-end {{ $totalKeuntungan >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($totalKeuntungan ?? 0, 0, ',', '.') }}
                        </td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Detail Transaksi -->
<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-list"></i> Detail Transaksi</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive-custom">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis Gas</th>
                        <th>Tipe</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end">Harga Jual</th>
                        <th class="text-end">Harga Beli</th>
                        <th class="text-end">Keuntungan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($detailLaba ?? [] as $item)
                    <tr>
                        <td>{{ $item['tanggal']->format('d/m/Y') }}</td>
                        <td>{{ $item['jenis_gas'] }}</td>
                        <td>
                            <span class="badge bg-{{ $item['tipe'] == 'toko' ? 'info' : 'success' }}">
                                {{ ucfirst($item['tipe']) }}
                            </span>
                        </td>
                        <td class="text-center">{{ $item['jumlah'] }}</td>
                        <td class="text-end">Rp {{ number_format($item['harga_jual'], 0, ',', '.') }}</td>
                        <td class="text-end">Rp {{ number_format($item['harga_beli'], 0, ',', '.') }}</td>
                        <td class="text-end fw-bold {{ $item['keuntungan'] >= 0 ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($item['keuntungan'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="fas fa-inbox fa-2x text-muted"></i>
                            <p class="text-muted mt-2">Belum ada data transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Ringkasan per Jenis Gas -->
<div class="card mt-3">
    <div class="card-header bg-success text-white">
        <h5 class="mb-0"><i class="fas fa-chart-pie"></i> Ringkasan per Jenis Gas</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive-custom">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Jenis Gas</th>
                        <th class="text-center">Total Jual</th>
                        <th class="text-end">Total Keuntungan</th>
                        <th class="text-center">Rata-rata / Tabung</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ringkasan ?? [] as $nama => $data)
                    <tr>
                        <td><strong>{{ $nama }}</strong></td>
                        <td class="text-center">{{ $data['total_jual'] }}</td>
                        <td class="text-end text-success">
                            Rp {{ number_format($data['total_keuntungan'], 0, ',', '.') }}
                        </td>
                        <td class="text-center">
                            @if($data['total_jual'] > 0)
                                Rp {{ number_format($data['total_keuntungan'] / $data['total_jual'], 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Belum ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection