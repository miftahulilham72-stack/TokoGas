@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header d-flex justify-content-between align-items-center">
    <div>
        <h1><i class="fas fa-home"></i> Dashboard</h1>
        <p class="text-muted mb-0">
            Selamat datang, <strong>{{ Session::get('username') ?? 'Admin' }}</strong>! 
            {{ date('l, d F Y') }}
        </p>
    </div>
    <div>
        <span class="badge bg-primary badge-minimum">
            <i class="fas fa-clock"></i> {{ date('H:i') }}
        </span>
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card card-stats bg-primary text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Stok</h6>
                        <h2 class="mb-0">{{ $totalStok }}</h2>
                        <small>Tabung</small>
                    </div>
                    <div class="stat-icon bg-white bg-opacity-25">
                        <i class="fas fa-boxes text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card card-stats bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Penjualan Hari Ini</h6>
                        <h2 class="mb-0">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</h2>
                        <small>{{ $jumlahPenjualanHariIni }} tabung</small>
                    </div>
                    <div class="stat-icon bg-white bg-opacity-25">
                        <i class="fas fa-shopping-cart text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card card-stats bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Penjualan Minggu Ini</h6>
                        <h2 class="mb-0">Rp {{ number_format($penjualanMingguIni, 0, ',', '.') }}</h2>
                        <small>Total</small>
                    </div>
                    <div class="stat-icon bg-white bg-opacity-25">
                        <i class="fas fa-chart-bar text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="card card-stats bg-warning text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-white-50 mb-1">Total Piutang</h6>
                        <h2 class="mb-0">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</h2>
                        <small>Belum dibayar</small>
                    </div>
                    <div class="stat-icon bg-white bg-opacity-25">
                        <i class="fas fa-hand-holding-usd text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stok Detail & Notifikasi -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-boxes"></i> Detail Stok</h5>
                <a href="{{ route('laporan.stok') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-eye"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Jenis Gas</th>
                                <th>Stok Saat Ini</th>
                                <th>Stok Minimum</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stokData as $item)
                            <tr>
                                <td><strong>{{ $item->nama }}</strong></td>
                                <td>
                                    <span class="fw-bold {{ ($item->stok && $item->stok->isMenipis()) ? 'text-danger' : 'text-success' }}">
                                        {{ $item->stok_sekarang }}
                                    </span>
                                </td>
                                <td>{{ $item->stok_minimum }}</td>
                                <td>
                                    @php
                                        $stok = $item->stok;
                                        if (!$stok) {
                                            $status = 'secondary';
                                            $label = 'Belum ada stok';
                                        } elseif ($stok->isMenipis()) {
                                            $status = 'danger';
                                            $label = '⚠️ Menipis';
                                        } else {
                                            $status = 'success';
                                            $label = '✅ Aman';
                                        }
                                    @endphp
                                    <span class="badge bg-{{ $status }} badge-minimum">{{ $label }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Notifikasi -->
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-bell"></i> Notifikasi</h5>
            </div>
            <div class="card-body">
                @if($stokMenipis->count() > 0)
                    @foreach($stokMenipis as $item)
                        <div class="alert alert-warning mb-2">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $item->nama }}</strong>
                                <span class="badge bg-danger">Kritis</span>
                            </div>
                            <small>
                                Stok: {{ $item->stok_sekarang }} 
                                (Min: {{ $item->stok_minimum }})
                            </small>
                            <br>
                            <small class="text-danger">
                                ⚠️ Segera beli!
                            </small>
                        </div>
                    @endforeach
                @else
                    <div class="alert alert-success mb-0">
                        <i class="fas fa-check-circle"></i> 
                        Semua stok dalam kondisi aman.
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Aksi Cepat -->
        <div class="card mt-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-bolt"></i> Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('pembelian.create') }}" class="btn btn-success">
                        <i class="fas fa-arrow-down"></i> Beli Gas
                    </a>
                    <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-up"></i> Jual Gas
                    </a>
                    <a href="{{ route('laporan.stok') }}" class="btn btn-info text-white">
                        <i class="fas fa-boxes"></i> Lihat Stok
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Info -->
        <div class="card mt-3 bg-light">
            <div class="card-body">
                <small class="text-muted">
                    <i class="fas fa-info-circle"></i> 
                    Sistem menggunakan metode FIFO (First In First Out) 
                    untuk perhitungan keuntungan.
                </small>
            </div>
        </div>
    </div>
</div>
@endsection