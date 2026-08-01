<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Toko Gas') - Sistem Manajemen</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f2f5;
        }
        
        /* ===== SIDEBAR ===== */
        .sidebar {
            min-height: 100vh;
            background: #1a1a2e;
            color: white;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: #0f3460;
            border-radius: 10px;
        }
        
        .sidebar .brand {
            padding: 20px;
            font-size: 24px;
            font-weight: bold;
            border-bottom: 1px solid #333;
            text-align: center;
        }
        
        .sidebar .brand i {
            color: #f7971e;
        }
        
        .sidebar .brand span {
            color: #f7971e;
        }
        
        .sidebar .nav {
            padding: 10px 0;
        }
        
        .sidebar .nav-link {
            color: #a8a8b3;
            padding: 10px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s;
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .sidebar .nav-link:hover {
            background: #16213e;
            color: white;
        }
        
        .sidebar .nav-link.active {
            background: #0f3460;
            color: white;
        }
        
        .sidebar .nav-link i {
            width: 25px;
            font-size: 16px;
        }
        
        .sidebar .menu-label {
            padding: 10px 20px 5px 20px;
            font-size: 11px;
            text-transform: uppercase;
            color: #6c757d;
            letter-spacing: 1px;
            font-weight: 600;
        }
        
        .sidebar .logout-link {
            border-top: 1px solid #333;
            margin-top: 10px;
            padding-top: 10px;
        }
        
        .sidebar .logout-link .nav-link {
            color: #dc3545;
        }
        
        .sidebar .logout-link .nav-link:hover {
            background: #2d1b1b;
            color: #ff6b6b;
        }
        
        /* ===== MAIN CONTENT ===== */
        .main-content {
            min-height: 100vh;
            padding: 25px 30px;
        }
        
        /* ===== STATS CARDS ===== */
        .card-stats {
            transition: transform 0.2s, box-shadow 0.2s;
            border: none;
            border-radius: 15px;
        }
        
        .card-stats:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }
        
        .card-stats .card-body {
            padding: 20px 25px;
        }
        
        .card-stats .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        /* ===== TABLE ===== */
        .table-actions {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        .table-actions .btn {
            padding: 4px 10px;
            font-size: 13px;
        }
        
        /* ===== BADGE ===== */
        .badge-minimum {
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
        }
        
        /* ===== PAGE HEADER ===== */
        .page-header {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .page-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a2e;
        }
        
        .page-header h1 i {
            color: #f7971e;
        }
        
        .page-header .text-muted {
            font-size: 14px;
        }
        
        /* ===== ALERT ===== */
        .alert {
            border-radius: 12px;
            border: none;
        }
        
        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
                height: auto;
                position: relative;
            }
            
            .main-content {
                padding: 15px;
            }
            
            .card-stats {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- ========== SIDEBAR ========== -->
        <div class="col-md-2 col-lg-2 px-0 sidebar">
            <div class="brand">
                <i class="fas fa-fire"></i> Toko<span>Gas</span>
            </div>
            
            <nav class="nav flex-column">
                <!-- DASHBOARD -->
                <div class="menu-label">Menu Utama</div>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                
                <!-- MASTER DATA -->
                <div class="menu-label">Master Data</div>
                <a href="{{ route('agen.index') }}" class="nav-link {{ request()->routeIs('agen.*') ? 'active' : '' }}">
                    <i class="fas fa-truck"></i> Agen
                </a>
                <a href="{{ route('jenis-gas.index') }}" class="nav-link {{ request()->routeIs('jenis-gas.*') ? 'active' : '' }}">
                    <i class="fas fa-fire"></i> Jenis Gas
                </a>
                
                <!-- TRANSAKSI -->
                <div class="menu-label">Transaksi</div>
                <a href="{{ route('pembelian.index') }}" class="nav-link {{ request()->routeIs('pembelian.*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-down"></i> Pembelian
                </a>
                <a href="{{ route('penjualan.index') }}" class="nav-link {{ request()->routeIs('penjualan.*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-up"></i> Penjualan
                </a>
                <a href="{{ route('piutang.index') }}" class="nav-link {{ request()->routeIs('piutang.*') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-usd"></i> Piutang
                </a>
                
                <!-- LAPORAN -->
                <div class="menu-label">Laporan</div>
                <a href="{{ route('laporan.stok') }}" class="nav-link {{ request()->routeIs('laporan.stok') ? 'active' : '' }}">
                    <i class="fas fa-boxes"></i> Stok
                </a>
                <a href="{{ route('laporan.laba') }}" class="nav-link {{ request()->routeIs('laporan.laba') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> Laba / Rugi
                </a>
                <a href="{{ route('laporan.piutang') }}" class="nav-link {{ request()->routeIs('laporan.piutang') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice"></i> Piutang
                </a>
                
                <!-- LOGOUT -->
                <div class="logout-link">
                    <div class="menu-label">Akun</div>
                    <a href="{{ route('logout') }}" class="nav-link" 
                       onclick="return confirm('Yakin ingin logout?')">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </nav>
        </div>

        <!-- ========== MAIN CONTENT ========== -->
        <div class="col-md-10 col-lg-10 main-content">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

@yield('scripts')

</body>
</html>