@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center flex-wrap">
    <div>
        <h1><i class="fas fa-home"></i> Dashboard</h1>
        <p class="text-muted mb-0">
            Selamat datang, <strong>{{ Session::get('username') ?? 'Admin' }}</strong>! 
            <span class="badge bg-primary badge-minimum">
                @php
                    $roleIcon = [
                        'admin' => '👑 Admin',
                        'kasir' => '💳 Kasir',
                        'owner' => '🏢 Owner'
                    ];
                @endphp
                {{ $roleIcon[Session::get('role', '')] ?? '👤 User' }}
            </span>
            <span class="d-none d-sm-inline">{{ date('l, d F Y') }}</span>
            <span class="d-sm-none">{{ date('d/m/Y') }}</span>
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="user-badge">
            <span class="avatar">{{ strtoupper(substr(Session::get('username') ?? 'Admin', 0, 1)) }}</span>
            <span class="d-none d-sm-inline">{{ Session::get('username') ?? 'Admin' }}</span>
        </span>
        <span class="badge bg-light text-dark px-3 py-2" id="realTimeClock">
            <i class="far fa-clock me-1"></i> <span id="clockDisplay">{{ date('H:i:s') }}</span>
        </span>
    </div>
</div>

<!-- Stats Cards -->
<div class="stats-grid">
    <div class="stat-card primary animate-fade-in stagger-1">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-label">Total Stok</div>
                <div class="stat-value">{{ $totalStok }}</div>
                <small class="text-muted">Tabung</small>
            </div>
            <div class="stat-icon">
                <i class="fas fa-boxes"></i>
            </div>
        </div>
    </div>

    <div class="stat-card success animate-fade-in stagger-2">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-label">Jual Hari Ini</div>
                <div class="stat-value">Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</div>
                <small class="text-muted">{{ $jumlahPenjualanHariIni }} tabung</small>
            </div>
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
        </div>
    </div>

    <div class="stat-card info animate-fade-in stagger-3">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-label">Jual Minggu Ini</div>
                <div class="stat-value">Rp {{ number_format($penjualanMingguIni, 0, ',', '.') }}</div>
                <small class="text-muted">Total</small>
            </div>
            <div class="stat-icon">
                <i class="fas fa-chart-bar"></i>
            </div>
        </div>
    </div>

    <div class="stat-card warning animate-fade-in stagger-4">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="stat-label">Total Piutang</div>
                <div class="stat-value">Rp {{ number_format($totalPiutang, 0, ',', '.') }}</div>
                <small class="text-muted">Belum dibayar</small>
            </div>
            <div class="stat-icon">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
        </div>
    </div>
</div>

<!-- Content Row -->
<div class="row g-3">
    <!-- Stok Detail -->
    <div class="col-lg-7 col-xl-8">
        <div class="card-modern animate-fade-in">
            <div class="card-header">
                <h5><i class="fas fa-boxes"></i> Detail Stok</h5>
                <a href="{{ route('laporan.stok') }}" class="btn btn-sm btn-primary-custom">
                    <i class="fas fa-eye"></i> Lihat Semua
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-custom mb-0">
                        <thead>
                            <tr>
                                <th>Jenis Gas</th>
                                <th class="text-center">Stok</th>
                                <th class="text-center d-none d-sm-table-cell">Minimum</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stokData as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->nama }}</strong>
                                    <br class="d-block d-sm-none">
                                    <small class="text-muted d-sm-none">Min: {{ $item->stok_minimum }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold {{ ($item->stok && $item->stok->isMenipis()) ? 'text-danger' : 'text-success' }}">
                                        {{ $item->stok_sekarang }}
                                    </span>
                                </td>
                                <td class="text-center d-none d-sm-table-cell">{{ $item->stok_minimum }}</td>
                                <td class="text-center">
                                    @php
                                        $stok = $item->stok;
                                        if (!$stok) {
                                            $status = 'secondary';
                                            $label = 'Kosong';
                                        } elseif ($stok->isMenipis()) {
                                            $status = 'danger';
                                            $label = '⚠️ Menipis';
                                        } else {
                                            $status = 'success';
                                            $label = '✅ Aman';
                                        }
                                    @endphp
                                    <span class="badge-custom bg-{{ $status }} text-white">{{ $label }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <i class="fas fa-inbox fa-2x text-muted"></i>
                                    <p class="text-muted mt-2">Belum ada data stok</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-5 col-xl-4">
        <!-- Notifikasi -->
        <div class="card-modern animate-fade-in">
            <div class="card-header bg-danger text-white" style="background: linear-gradient(135deg, #dc2626, #ef4444) !important;">
                <h5 class="text-white"><i class="fas fa-bell"></i> Notifikasi</h5>
            </div>
            <div class="card-body">
                @if($stokMenipis->count() > 0)
                    @foreach($stokMenipis as $item)
                        <div class="alert alert-warning mb-2 p-3" style="border-left: 4px solid #f59e0b;">
                            <div class="d-flex justify-content-between align-items-center">
                                <strong>{{ $item->nama }}</strong>
                                <span class="badge bg-danger">Kritis</span>
                            </div>
                            <small>
                                Stok: {{ $item->stok_sekarang }} 
                                (Min: {{ $item->stok_minimum }})
                            </small>
                            <br>
                            <small class="text-danger fw-bold">
                                ⚠️ Segera beli!
                            </small>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-check-circle fa-2x text-success"></i>
                        <p class="text-muted mt-2">Semua stok dalam kondisi aman</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Aksi Cepat -->
        <div class="card-modern animate-fade-in mt-3">
            <div class="card-header" style="background: linear-gradient(135deg, var(--secondary), var(--secondary-light)) !important;">
                <h5 class="text-white"><i class="fas fa-bolt"></i> Aksi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('pembelian.create') }}" class="btn btn-success py-2" style="border-radius: var(--radius-sm); font-weight: 600;">
                        <i class="fas fa-arrow-down me-2"></i> Beli Gas
                    </a>
                    <a href="{{ route('penjualan.create') }}" class="btn btn-primary-custom py-2" style="border-radius: var(--radius-sm);">
                        <i class="fas fa-arrow-up me-2"></i> Jual Gas
                    </a>
                    <a href="{{ route('laporan.stok') }}" class="btn btn-info text-white py-2" style="border-radius: var(--radius-sm); font-weight: 600; background: #3b82f6;">
                        <i class="fas fa-boxes me-2"></i> Lihat Stok
                    </a>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="card-modern animate-fade-in mt-3" style="background: linear-gradient(135deg, #f8f9fa, #e9ecef);">
            <div class="card-body">
                <div class="d-flex align-items-start gap-3">
                    <i class="fas fa-info-circle text-primary" style="font-size: 20px;"></i>
                    <div>
                        <small class="text-muted">
                            <strong>Sistem FIFO</strong><br>
                            Keuntungan dihitung dengan metode<br>
                            First In First Out (FIFO) untuk akurasi.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// ===== REAL TIME CLOCK =====
function updateClock() {
    const now = new Date();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    const seconds = String(now.getSeconds()).padStart(2, '0');
    document.getElementById('clockDisplay').textContent = hours + ':' + minutes + ':' + seconds;
}

// Update setiap 1 detik
setInterval(updateClock, 1000);

// Jalankan pertama kali
updateClock();
</script>
@endsection