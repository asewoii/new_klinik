<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Tambah Pasien</title>
    <link href="https://unpkg.com/tabulator-tables@5.6.3/dist/css/tabulator.min.css" rel="stylesheet">

    <style>
        :root {
            /* Warna Utama */
            --medical-blue: #2563eb;
            --medical-teal: #0891b2;
            --medical-mint: #10b981;

            /* Warna Background */
            --bg-medical-light: #f0f9ff;
            --bg-medical-white: #ffffff;
            --bg-medical-gray: #f8fafc;

            /* Warna Notif */
            --notif-accent-green: #059669;
            --notif-accent-orange: #ea580c;
            --notif-accent-red: #dc2626;

            /* Warna Teks */
            --text-dark: #1e293b;
            --text-light: #64748b;
            --text-muted: #94a3b8;

            /* Shadow & Effects */
            --shadow-medical: 0 4px 6px -1px rgba(37, 99, 235, 0.1), 0 2px 4px -1px rgba(37, 99, 235, 0.06);
            --shadow-card: 0 10px 15px -3px rgba(37, 99, 235, 0.1), 0 4px 6px -2px rgba(37, 99, 235, 0.05);
            --glow-medical: 0 0 20px rgba(37, 99, 235, 0.15);

            /* Warna Status Medis */
            --status-normal: #10b981;
            --status-warning: #f59e0b;
            --status-critical: #ef4444;
            --status-info: #3b82f6;

            /* Gradients */
            --gradient-primary: linear-gradient(135deg, var(--medical-blue) 0%, var(--medical-teal) 100%);
            --gradient-success: linear-gradient(135deg, var(--medical-mint) 0%, var(--notif-accent-green) 100%);
            --gradient-background: linear-gradient(135deg, var(--bg-medical-light) 0%, var(--bg-medical-white) 100%);

            /* Warna tambahan hasil konversi rgba */
            --overlay-light: rgba(255, 255, 255, 0.15);
            --overlay-hover: rgba(255, 255, 255, 0.25);
            --overlay-border: rgba(255, 255, 255, 0.2);
            --overlay-border-light: var(--overlay-border-light);
            --blue-light-transparent: rgba(37, 99, 235, 0.05);
            --blue-border-transparent: rgba(37, 99, 235, 0.1);
            --blue-focus-shadow: rgba(37, 99, 235, 0.15);

            /* Transisi */
            --transition-default: all .3s ease-in-out;

            /* Warna Fokus */
            --focus-ring: #93c5fd;

            /* Warna Kategori Layanan */
            --category-obgyn: #f472b6;
            --category-pediatric: #facc15;
            --category-dental: #a78bfa;

            /* Warna Netral Tambahan */
            --gray-light: #e2e8f0;
            --gray-dark: #cbd5e1;

            /* Tombol Sekunder & Cancel */
            --button-secondary: #e0f2fe;
            --button-cancel: #fef2f2;

            /* Background Gelap */
            --bg-dark: #1e293b;
            --bg-dark-soft: #334155;
        }

        body {
            background: var(--gradient-background);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            margin: 0;
            transition: var(--transition-default);
            color: var(--text-dark);
        }

        .sidebar {
            min-height: 100vh;
            background-color: var(--bg-medical-white);
            border-radius: 12px;
            box-shadow: var(--shadow-card);
            display: flex;
            flex-direction: column;
            padding: 20px;
            min-width: 14rem;
            max-width: 14rem;
            transition: var(--transition-default);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--text-dark);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: var(--transition-default);
        }

        .brand-icon {
            width: 40px;
            height: 40px;
            background: var(--overlay-border);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            backdrop-filter: blur(10px);
            border: 1px solid var(--overlay-border);
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
            margin-top: -15px;
        }

        .brand-main {
            font-size: 1.3rem;
            font-weight: 700;
        }

        .brand-sub {
            font-size: 0.75rem;
            opacity: 0.9;
            font-weight: 400;
        }

        .garisss {
            border-top: 1px solid var(--bg-medical-gray);
            margin-bottom: 1rem;
        }

        .nav {
            text-align: left;
        }

        .nav-item {
            margin-bottom: 10px;
            transition: var(--transition-default);
        }

        .nav-item:hover,
        .navbar-nav .nav-link:hover {
            background: var(--bg-medical-light);
            transform: translateY(-1px);
            color: var(--medical-blue);
            backdrop-filter: blur(10px);
        }

        .navbar-nav .nav-link {
            color: var(--text-muted);
            font-weight: 500;
            margin: 0 8px;
            padding: 8px 16px;
            border-radius: 8px;
            display: block;
            text-decoration: none;
            transition: var(--transition-default);
        }

        .navbar-nav .nav-link.active {
            background: var(--bg-medical-light);
            color: var(--medical-blue);
            font-weight: bold;
            box-shadow: var(--shadow-medical);
            backdrop-filter: blur(15px);
        }

        .medical-navbar {
            background: var(--gradient-primary);
            box-shadow: var(--shadow-card);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--overlay-border-light);
            position: sticky;
            top: 0;
            z-index: 1;
            padding: 0.1rem 0;
        }

        .dropdown-menu {
            background-color: var(--bg-medical-white);
            border: none;
            padding: 0.4rem;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-card);
            transition: var(--transition-default);
        }

        .dropdown-item {
            margin: 5px 0;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            color: var(--text-dark);
            transition: var(--transition-default);
        }

        .dropdown-item:hover,
        .dropdown-item.active {
            background-color: var(--bg-medical-light);
            color: var(--medical-blue);
            font-weight: bold;
        }

        .notification-btn {
            background: var(--overlay-light);
            border: 1px solid var(--overlay-border);
            color: var(--bg-medical-white);
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition-default);
            position: relative;
        }

        .notification-btn:hover {
            background: var(--overlay-hover);
            transform: translateY(-1px);
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--notif-accent-red);
            color: var(--bg-medical-white);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 0.7rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .notification-success {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--notif-accent-green);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-medical);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            z-index: 3;
        }

        .notification-error {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--notif-accent-red);
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            box-shadow: var(--shadow-medical);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            z-index: 3;
        }

        .notification.show {
            transform: translateX(0);
        }

        #sidebar {
            width: 250px;
            min-height: 100vh;
            transition: var(--transition-default);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 2;
            background-color: var(--bg-medical-white);
        }

        #page-content {
            margin-left: 250px;
            transition: var(--transition-default);
            padding: 1.5rem;
            max-width: 170vh;
        }

        #wrapper.sidebar-toggled #sidebar {
            margin-left: -250px;
        }

        #wrapper.sidebar-toggled #page-content {
            margin-left: 0;
            min-width: 100%;
        }

        /* Dropdown Loqout Admin */
        .dropdown-item:hover {
            background: linear-gradient(135deg, var(--medical-light) 0%, rgba(37, 99, 235, 0.05) 100%);
            color: var(--medical-blue);
        }

        .breadcrumb {
            background: linear-gradient(135deg, var(--medical-light) 0%, rgba(37, 99, 235, 0.05) 100%);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            border: 1px solid var(--blue-border-transparent);
            margin-bottom: 1.5rem;
        }

        .breadcrumb-item a {
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            color: var(--medical-blue);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: var(--medical-teal);
            transform: translateX(2px);
        }

        .breadcrumb-item.active {
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            color: var(--text-light);
            font-weight: 600;
        }

        /* Search Form + Tambah + Delete */
        .cardstd {
            background: var(--medical-white);
            border-radius: 16px;
            border: 1px solid var(--blue-border-transparent);
            min-height: 10rem;
            max-height: 12rem;
            overflow: hidden;
        }

        .form-control,
        .form-select {
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            border-radius: 10px;
            border: 2px solid var(--blue-border-transparent);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--medical-blue);
            box-shadow: 0 0 0 0.2rem var(--overlay-light);
        }

        .input-group .btn {
            border-radius: 0 10px 10px 0;
            padding: 0.75rem 1.25rem;
        }

        /* Statistics Card */
        .stats-card {
            background: linear-gradient(135deg, var(--medical-light) 0%, var(-blue-light-transparent) 100%);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1rem;
            border-left: 4px solid var(--medical-teal);
        }

        .stats-text {
            color: var(--text-light);
            font-weight: 500;
        }

        .stats-number {
            color: var(--medical-blue);
            font-weight: 700;
        }

        .action-buttons {
            display: flex;
            gap: 0.75rem;
            flex-wrap: wrap;
            width: auto;
        }

        /* Table */
        .custom-table th,
        .custom-table td {
            vertical-align: middle;
            text-align: center;
            white-space: nowrap;
            font-size: 0.875rem;
            padding: 0.6rem;
        }

        .custom-table th {
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-color: #e3f2fd;
            /* soft blue */
            color: #0d47a1;
            font-weight: 600;
        }

        .custom-table tbody tr:hover {
            background-color: #f1faff;
        }

        .custom-table td .btn {
            font-size: 0.75rem;
            padding: 0.3rem 0.5rem;
        }

        .custom-table input[type="checkbox"] {
            width: 1rem;
            height: 1rem;
        }

        .custom-table td:first-child,
        .custom-table th:first-child {
            width: 40px;
        }

        .custom-table td:nth-child(2),
        .custom-table th:nth-child(2) {
            min-width: 110px;
        }

        .custom-table td:nth-child(5) {
            white-space: normal;
            /* Deskripsi panjang bisa wrap */
            text-align: left;
        }

        .daterangepicker {
            right: 29px !important;
        }

        .live_waktu {
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
            background-color: var(--bg-medical-light);
            color: var(--text-muted);
            border-radius: 5px;
        }

        .anti_salin {
            -webkit-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        .card_table {
            max-width: 170vh;
        }

        .notification-container {
            background-color: var(--bg-medical-light);
            border-radius: 8px;
            padding: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .notification-list {
            list-style: none;
        }

        .notification-item {
            border: 1px solid var(--shadow-medical);
            border-radius: 6px;
            padding: 5px;
            margin-bottom: 15px;
            background-color: var(--bg-medical-white);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .notification-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 15px var(--shadow-medical);
        }

        .notification-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .notification-category {
            font-weight: 500;
            color: #3498db;
            display: flex;
            align-items: center;
        }

        .notification-category i {
            margin-right: 5px;
        }

        .notification-content {
            margin-bottom: 10px;
        }

        .notification-text {
            display: flex;
            align-items: flex-start;
        }

        .notification-text i {
            margin-right: 5px;
            color: #e74c3c;
            margin-top: 3px;
        }

        .notification-text strong {
            font-weight: 500;
        }

        .notification-footer {
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            color: #7f8c8d;
        }

        .notification-footer i {
            margin-right: 5px;
        }

        .notification-footer b {
            color: #7f8c8d;
        }

        .timetable {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }
        .timetable th, .timetable td {
            border: 1px solid #dee2e6;
            padding: 0.75rem;
        }
        .timetable th {
            background-color: #0d6efd;
            color: #fff;
        }
        .keterangan-col {
            background-color: #f8f9fa;
            font-weight: bold;
            text-align: left;
        }



        /* Global Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @media (max-width: 575.98px) {
            .sidebar {
                display: none;
                /* Sembunyikan sidebar di layar kecil */
            }

            #page-content {
                padding: 0.75rem;
                margin-left: 0;
            }

            .navbar-brand {
                font-size: 1.1rem;
                gap: 8px;
            }

            .brand-icon {
                width: 32px;
                height: 32px;
                font-size: 1rem;
            }

            .brand-main {
                font-size: 1rem;
            }

            .brand-sub {
                font-size: 0.65rem;
            }

            .form-control,
            .form-select {
                padding: 0.5rem 0.75rem;
                font-size: 0.875rem;
            }

            .stats-card {
                padding: 0.75rem 1rem;
            }

            .action-buttons {
                flex-direction: column;
                align-items: stretch;
            }

            .custom-table th,
            .custom-table td {
                font-size: 0.75rem;
                padding: 0.4rem;
            }

            .notification-success,
            .notification-error {
                top: 10px;
                right: 10px;
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
        }

        @media (max-width: 991.98px) {
            #sidebar {
                position: fixed;
                left: 0;
                top: 0;
                z-index: 2;
                transform: translateX(-100%);
            }

            #wrapper.sidebar-toggled #sidebar {
                transform: translateX(0);
            }

            #page-content {
                padding: 1rem;
                margin-left: 0;

            }

            .medical-navbar {
                padding: 0.5rem;
            }

            .navbar-collapse {
                background: var(--medical-blue);
                backdrop-filter: blur(20px);
                border-radius: 12px;
                margin-top: 15px;
                padding: 20px;
                border: 1px solid var(--overlay-border-light);
            }

            .navbar-nav .nav-link {
                margin: 5px 0;
                padding: 12px 16px;
            }

            .user-menu {
                margin-top: 15px;
                padding-top: 15px;
                border-top: 1px solid var(--overlay-border-light);
                justify-content: center;
            }
        }




        /* Area konten utama */
        .flex-grow-1 {
            background-color: var(--white);
            /* Latar putih */
            border-radius: 12px;
            /* Sudut membulat */
            box-shadow: 0 6px 30px rgba(0, 0, 0, 0.06);
            /* Bayangan lembut */
            padding: 30px;
            /* Spasi dalam */
            margin: 0px 5px;
            /* Jarak antar elemen */
            transition: box-shadow 0.3s ease-in-out;
            /* Transisi bayangan */
        }
    </style>
</head>

<body class="bg-light">
    <div class="d-flex" id="wrapper">    
        @if (Auth::check() && Auth::user()->role == 'admin')
        <div class="anti_salin sidebar shadow" id="sidebar">
            <a class="navbar-brand text-decoration-none" href="/admin">
                <div class="brand-icon">
                    <i class="bi bi-hospital-fill"></i>
                </div>
                <div class="brand-text">
                    <span class="brand-main">MediCare</span>
                    <span class="brand-sub">Admin Dashboard</span>
                </div>
            </a>

            <div class="garisss"></div>

            <ul class="nav navbar-nav mx-auto">
                <li class="nav-item">
                    <a href="{{ route('admin') }}" class="nav-link {{ request()->routeIs('admin') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('indikasi.index') }}" class="nav-link {{ request()->routeIs('indikasi.*') ? 'active' : '' }}">
                        <i class="fa fa-heartbeat"></i>
                        <span>Keluhan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('sesi.index') }}" class="nav-link {{ request()->routeIs('sesi.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check-fill"></i>
                        <span>Sesi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dokter.index') }}" class="nav-link {{ request()->routeIs('dokter.*') ? 'active' : '' }}">
                        <i class="fa fa-user-md"></i>
                        <span>Terapis</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('jadwal.index') }}" class="nav-link {{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-week"></i>
                        <span>Jadwal</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pasien.index') }}" class="nav-link {{ request()->routeIs('pasien.*') ? 'active' : '' }}">
                        <i class="bi bi-person-vcard-fill"></i>
                        <span>Pasien</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('kunjungan.index') }}" class="nav-link {{ request()->routeIs('kunjungan.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        <span>Kunjungan</span>
                    </a>
                </li>
            </ul>
        </div>
        @endif

        <!-- Main Content -->
        <div class="d-flex flex-column min-vh-100 w-100" id="page-content">
            @yield('content')
        </div>
    </div>
<script src="https://unpkg.com/tabulator-tables@5.6.3/dist/js/tabulator.min.js"></script>

</body>
</html>
