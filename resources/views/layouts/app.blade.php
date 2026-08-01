<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Toko Gas') - Sistem Manajemen</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
                   ROOT VARIABLES
                ============================================ */
        :root {
            --primary: #f7971e;
            --primary-dark: #e07c0a;
            --primary-light: #ffd700;
            --secondary: #1a1a2e;
            --secondary-light: #2d2d44;
            --bg-gradient: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            --card-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            --card-hover-shadow: 0 12px 48px rgba(0, 0, 0, 0.18);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            --radius: 16px;
            --radius-sm: 10px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f0f2f5;
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* ============================================
                   SCROLLBAR
                ============================================ */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #e9ecef;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }

        /* ============================================
                   SIDEBAR
                ============================================ */
        .sidebar {
            min-height: 100vh;
            background: var(--secondary);
            color: white;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1050;
            box-shadow: 4px 0 30px rgba(0, 0, 0, 0.2);
        }

        .sidebar .brand {
            padding: 18px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            gap: 12px;
            position: sticky;
            top: 0;
            background: var(--secondary);
            z-index: 10;
        }

        .sidebar .brand .logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--secondary);
            flex-shrink: 0;
        }

        .sidebar .brand h4 {
            font-size: 20px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .sidebar .brand h4 span {
            color: var(--primary);
        }
        .sidebar .brand small {
            font-size: 10px;
            color: #6c757d;
            font-weight: 400;
            display: block;
            margin-top: -2px;
            line-height: 1.4;
        }

        .sidebar .nav {
            padding: 8px 0 20px 0;
        }

        .sidebar .menu-label {
            padding: 16px 22px 6px 22px;
            font-size: 10px;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.3);
            letter-spacing: 1.5px;
            font-weight: 700;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.6);
            padding: 10px 20px;
            margin: 2px 12px;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
            font-weight: 500;
            position: relative;
        }

        .sidebar .nav-link i {
            width: 20px;
            font-size: 16px;
            text-align: center;
            transition: var(--transition);
        }

        .sidebar .nav-link:hover {
            background: rgba(255, 255, 255, 0.06);
            color: white;
            transform: translateX(4px);
        }

        .sidebar .nav-link:hover i {
            color: var(--primary);
        }

        .sidebar .nav-link.active {
            background: rgba(247, 151, 30, 0.15);
            color: white;
            box-shadow: inset 3px 0 0 var(--primary);
        }

        .sidebar .nav-link.active i {
            color: var(--primary);
        }

        .sidebar .nav-link .badge-dot {
            width: 6px;
            height: 6px;
            background: var(--primary);
            border-radius: 50%;
            margin-left: auto;
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(0.8); }
        }

        .sidebar .logout-link {
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            margin-top: 10px;
            padding-top: 10px;
        }

        .sidebar .logout-link .nav-link {
            color: rgba(255, 107, 107, 0.7);
        }
        .sidebar .logout-link .nav-link:hover {
            color: #ff6b6b;
            background: rgba(255, 107, 107, 0.1);
        }

        /* ============================================
                   SIDEBAR TOGGLE
                ============================================ */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 12px;
            left: 12px;
            z-index: 1060;
            background: var(--secondary);
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: var(--radius-sm);
            font-size: 18px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: var(--transition);
        }

        .sidebar-toggle:hover {
            background: var(--primary);
            transform: scale(1.05);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            backdrop-filter: blur(4px);
        }
        .sidebar-overlay.active {
            display: block;
        }

        /* ============================================
                   MAIN CONTENT
                ============================================ */
        .main-content {
            min-height: 100vh;
            padding: 24px 28px 40px 28px;
            transition: var(--transition);
            background: #f0f2f5;
        }

        /* ============================================
                   PAGE HEADER
                ============================================ */
        .page-header {
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 2px solid rgba(0, 0, 0, 0.05);
        }

        .page-header h1 {
            font-size: 24px;
            font-weight: 800;
            color: var(--secondary);
            letter-spacing: -0.5px;
        }
        .page-header h1 i {
            color: var(--primary);
            margin-right: 8px;
        }
        .page-header .text-muted {
            font-size: 13px;
            color: #6c757d;
        }

        .page-header .user-badge {
            background: white;
            padding: 6px 16px 6px 12px;
            border-radius: 50px;
            box-shadow: var(--card-shadow);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 500;
        }
        .page-header .user-badge .avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary);
            font-weight: 700;
            font-size: 14px;
        }

        /* ============================================
                   STATS CARDS - MODERN
                ============================================ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border-radius: var(--radius);
            padding: 20px 22px;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.04);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            border-radius: var(--radius) var(--radius) 0 0;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--card-hover-shadow);
        }

        .stat-card .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .stat-card .stat-label {
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card .stat-value {
            font-size: 28px;
            font-weight: 800;
            color: var(--secondary);
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .stat-card .stat-change {
            font-size: 12px;
            font-weight: 600;
            padding: 2px 10px;
            border-radius: 50px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .stat-card .stat-change.positive {
            color: #22c55e;
            background: rgba(34, 197, 94, 0.1);
        }
        .stat-card .stat-change.negative {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
        }

        /* Warna per card */
        .stat-card.primary::before { background: var(--primary); }
        .stat-card.primary .stat-icon { background: rgba(247, 151, 30, 0.12); color: var(--primary); }
        .stat-card.primary .stat-value { color: var(--primary); }

        .stat-card.success::before { background: #22c55e; }
        .stat-card.success .stat-icon { background: rgba(34, 197, 94, 0.12); color: #22c55e; }
        .stat-card.success .stat-value { color: #22c55e; }

        .stat-card.info::before { background: #3b82f6; }
        .stat-card.info .stat-icon { background: rgba(59, 130, 246, 0.12); color: #3b82f6; }
        .stat-card.info .stat-value { color: #3b82f6; }

        .stat-card.warning::before { background: #f59e0b; }
        .stat-card.warning .stat-icon { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
        .stat-card.warning .stat-value { color: #f59e0b; }

        /* ============================================
                   CARDS
                ============================================ */
        .card-modern {
            background: white;
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
            border: 1px solid rgba(0, 0, 0, 0.04);
            transition: var(--transition);
            overflow: hidden;
        }

        .card-modern:hover {
            box-shadow: var(--card-hover-shadow);
        }

        .card-modern .card-header {
            padding: 16px 22px;
            background: transparent;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }

        .card-modern .card-header h5 {
            font-size: 15px;
            font-weight: 700;
            margin: 0;
            color: var(--secondary);
        }
        .card-modern .card-header h5 i {
            color: var(--primary);
            margin-right: 8px;
        }

        .card-modern .card-body {
            padding: 20px 22px;
        }
        .card-modern .card-body.p-0 {
            padding: 0;
        }

        /* ============================================
                   TABLE
                ============================================ */
        .table-custom {
            font-size: 13px;
        }
        .table-custom thead th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 2px solid #e9ecef;
        }
        .table-custom tbody td {
            padding: 10px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f5;
        }
        .table-custom tbody tr:hover {
            background: #f8f9fa;
        }

        /* ============================================
                   BUTTONS
                ============================================ */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            border: none;
            color: var(--secondary);
            font-weight: 700;
            padding: 10px 24px;
            border-radius: var(--radius-sm);
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(247, 151, 30, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(247, 151, 30, 0.4);
            color: var(--secondary);
        }

        .btn-ghost {
            background: transparent;
            border: 2px solid #e9ecef;
            font-weight: 600;
            padding: 8px 20px;
            border-radius: var(--radius-sm);
            transition: var(--transition);
        }
        .btn-ghost:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: rgba(247, 151, 30, 0.05);
        }

        /* ============================================
                   BADGE
                ============================================ */
        .badge-custom {
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 600;
        }

        /* ============================================
                   ALERT
                ============================================ */
        .alert-modern {
            border: none;
            border-radius: var(--radius-sm);
            padding: 14px 20px;
            font-size: 14px;
            box-shadow: var(--card-shadow);
        }

        /* ============================================
                   ANIMATIONS
                ============================================ */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.5s ease forwards;
        }

        .animate-slide-in {
            animation: slideIn 0.4s ease forwards;
        }

        .stagger-1 { animation-delay: 0.05s; }
        .stagger-2 { animation-delay: 0.1s; }
        .stagger-3 { animation-delay: 0.15s; }
        .stagger-4 { animation-delay: 0.2s; }

        /* ============================================
                   RESPONSIVE
                ============================================ */
        @media (max-width: 991.98px) {
            .sidebar-toggle {
                display: block;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: -290px;
                width: 290px;
                height: 100vh;
                z-index: 1050;
                box-shadow: 4px 0 40px rgba(0, 0, 0, 0.3);
            }

            .sidebar.open {
                left: 0;
            }

            .main-content {
                padding: 16px;
                padding-top: 72px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .stat-card .stat-value {
                font-size: 22px;
            }

            .page-header h1 {
                font-size: 20px;
            }
        }

        @media (max-width: 575.98px) {
            .main-content {
                padding: 10px;
                padding-top: 65px;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 8px;
            }

            .stat-card {
                padding: 14px 16px;
                border-radius: 12px;
            }
            .stat-card .stat-value {
                font-size: 18px;
            }
            .stat-card .stat-label {
                font-size: 10px;
            }
            .stat-card .stat-icon {
                width: 36px;
                height: 36px;
                font-size: 16px;
            }

            .page-header h1 {
                font-size: 17px;
            }
            .page-header .text-muted {
                font-size: 12px;
            }

            .card-modern .card-header {
                padding: 12px 16px;
            }
            .card-modern .card-body {
                padding: 14px 16px;
            }

            .table-custom {
                font-size: 12px;
            }
            .table-custom thead th,
            .table-custom tbody td {
                padding: 6px 10px;
                white-space: nowrap;
            }

            .btn-primary-custom {
                padding: 8px 16px;
                font-size: 13px;
            }
        }

        @media (max-width: 400px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 6px;
            }
            .stat-card {
                padding: 10px 12px;
            }
            .stat-card .stat-value {
                font-size: 16px;
            }
            .stat-card .stat-icon {
                width: 30px;
                height: 30px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<!-- ===== SIDEBAR OVERLAY ===== -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- ===== SIDEBAR TOGGLE ===== -->
<button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
    <i class="fas fa-bars"></i>
</button>

<div class="container-fluid">
    <div class="row">
        <!-- ===== SIDEBAR ===== -->
        <div class="col-md-2 col-lg-2 px-0 sidebar" id="sidebar">
            <div class="brand">
                <div class="logo-icon">
                    <i class="fas fa-fire"></i>
                </div>
                <div>
                    <h4>Toko<span>Gas</span></h4>
                    <small>
                        @php
                            $roleName = Session::get('role_name', '');
                            $roleIcon = [
                                'admin' => '👑',
                                'kasir' => '💳',
                                'owner' => '🏢'
                            ];
                            $roleIconDisplay = $roleIcon[Session::get('role', '')] ?? '👤';
                        @endphp
                        {{ $roleIconDisplay }} {{ $roleName }}
                        <span style="color: #6c757d; font-size: 9px; text-transform: uppercase;">
                            {{ Session::get('role', '') }}
                        </span>
                    </small>
                </div>
            </div>

            <nav class="nav flex-column">
                <div class="menu-label">Menu Utama</div>
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i> Dashboard
                    <span class="badge-dot"></span>
                </a>

                <div class="menu-label">Master Data</div>
                <a href="{{ route('agen.index') }}" class="nav-link {{ request()->routeIs('agen.*') ? 'active' : '' }}">
                    <i class="fas fa-truck"></i> Agen
                </a>
                <a href="{{ route('jenis-gas.index') }}" class="nav-link {{ request()->routeIs('jenis-gas.*') ? 'active' : '' }}">
                    <i class="fas fa-fire"></i> Jenis Gas
                </a>

                <div class="menu-label">Transaksi</div>
                <a href="{{ route('pembelian.index') }}" class="nav-link {{ request()->routeIs('pembelian.*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-down"></i> Pembelian
                </a>
                <a href="{{ route('penjualan.index') }}" class="nav-link {{ request()->routeIs('penjualan.*') ? 'active' : '' }}">
                    <i class="fas fa-arrow-up"></i> Penjualan
                </a>
                <a href="{{ route('piutang.index') }}" class="nav-link {{ request()->routeIs('piutang.*') ? 'active' : '' }}">
                    <i class="fas fa-hand-holding-usd"></i> Piutang
                    @if(isset($totalPiutang) && $totalPiutang > 0)
                        <span class="badge bg-danger ms-auto" style="font-size:10px;">{{ $totalPiutang }}</span>
                    @endif
                </a>

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

                <div class="logout-link">
                    <div class="menu-label">Akun</div>
                    <a href="{{ route('logout') }}" class="nav-link" 
                       onclick="return confirm('Yakin ingin logout?')">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </nav>
        </div>

        <!-- ===== MAIN CONTENT ===== -->
        <div class="col-md-10 col-lg-10 main-content" id="mainContent">
            <!-- Alert Messages -->
            @if(session('success'))
                <div class="alert alert-modern alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-modern alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<!-- ===== SCRIPTS ===== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<script>
$(document).ready(function() {
    // ===== SIDEBAR TOGGLE =====
    const sidebar = $('#sidebar');
    const overlay = $('#sidebarOverlay');
    const toggleBtn = $('#sidebarToggle');

    function openSidebar() {
        sidebar.addClass('open');
        overlay.addClass('active');
        toggleBtn.html('<i class="fas fa-times"></i>');
        $('body').css('overflow', 'hidden');
    }

    function closeSidebar() {
        sidebar.removeClass('open');
        overlay.removeClass('active');
        toggleBtn.html('<i class="fas fa-bars"></i>');
        $('body').css('overflow', '');
    }

    toggleBtn.click(function() {
        if (sidebar.hasClass('open')) {
            closeSidebar();
        } else {
            openSidebar();
        }
    });

    overlay.click(function() {
        closeSidebar();
    });

    $(window).resize(function() {
        if ($(window).width() > 991) {
            closeSidebar();
        }
    });

    // ===== AUTO CLOSE ALERT =====
    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);

    // ===== ANIMATION ON SCROLL =====
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.animate-fade-in').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        observer.observe(el);
    });
});
</script>

@yield('scripts')

</body>
</html>