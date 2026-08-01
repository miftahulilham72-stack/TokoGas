@extends('layouts.app')

@section('title', 'Laporan Laba / Rugi')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <h1><i class="fas fa-chart-line"></i> Laporan Laba / Rugi</h1>
    <div>
        <a href="{{ route('laporan.cetak-laba-pdf', ['bulan' => $bulan ?? date('m'), 'tahun' => $tahun ?? date('Y')]) }}" class="btn btn-danger" target="_blank">
            <i class="fas fa-file-pdf"></i> Cetak PDF
        </a>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <form action="{{ route('laporan.laba') }}" method="GET" class="row">
            <div class="col-md-4">
                <label for="bulan" class="form-label">Bulan</label>
                <select name="bulan" id="bulan" class="form-select">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ ($bulan ?? date('m')) == $i ? 'selected' : '' }}>
                            {{ date('F', mktime(0,0,0,$i,1)) }}
                        </option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4">
                <label for="tahun" class="form-label">Tahun</label>
                <select name="tahun" id="tahun" class="form-select">
                    @for($i = date('Y'); $i >= date('Y')-5; $i--)
                        <option value="{{ $i }}" {{ ($tahun ?? date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Tampilkan
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5>Detail Keuntungan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Gas</th>
                                <th>Tipe</th>
                                <th>Jumlah</th>
                                <th>Harga Jual</th>
                                <th>Harga Beli</th>
                                <th>Keuntungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($detailLaba ?? [] as $item)
                            <tr>
                                <td>{{ $item['tanggal']->format('d/m/Y') }}</td>
                                <td>{{ $item['jenis_gas'] }}</td>
                                <td>{{ ucfirst($item['tipe']) }}</td>
                                <td>{{ $item['jumlah'] }}</td>
                                <td>Rp {{ number_format($item['harga_jual'], 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item['harga_beli'], 0, ',', '.') }}</td>
                                <td class="text-success fw-bold">
                                    Rp {{ number_format($item['keuntungan'], 0, ',', '.') }}
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
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5>Ringkasan per Jenis Gas</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Jenis Gas</th>
                                <th>Total Jual</th>
                                <th>Total Keuntungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ringkasan ?? [] as $nama => $data)
                            <tr>
                                <td>{{ $nama }}</td>
                                <td>{{ $data['total_jual'] }}</td>
                                <td class="text-success">Rp {{ number_format($data['total_keuntungan'], 0, ',', '.') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center">Belum ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5>Total Keuntungan</h5>
            </div>
            <div class="card-body">
                <h2 class="text-success">
                    Rp {{ number_format($totalKeuntungan ?? 0, 0, ',', '.') }}
                </h2>
                <p class="text-muted">Periode: {{ date('F', mktime(0,0,0,$bulan ?? date('m'),1)) }} {{ $tahun ?? date('Y') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection