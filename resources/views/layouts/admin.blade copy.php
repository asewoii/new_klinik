<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>

    @include('layouts.partials.cdn.admincss')




    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
            --info-gradient: linear-gradient(135deg, #74b9ff 0%, #0984e3 100%);
            --warning-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
            --danger-gradient: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            --dark-gradient: linear-gradient(135deg, #232526 0%, #414345 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --shadow-soft: 0 10px 40px rgba(0, 0, 0, 0.1);
            --shadow-hover: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        /* === SIDEBAR WRAPPER === */
        .dashboard-sidebar {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        /* === KARTU UTAMA (NEUMORPHIC EFFECT) === */
        .dashboard-sidebar .card {
            border-radius: 1.25rem;
            background: #f9fafe;
            border: none;
            box-shadow:
                8px 8px 20px rgba(0, 0, 0, 0.06),
                -4px -4px 10px rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }

        .dashboard-sidebar .card:hover {
            transform: translateY(-4px);
            box-shadow:
                4px 4px 20px rgba(0, 0, 0, 0.08),
                -4px -4px 12px rgba(255, 255, 255, 0.9);
        }

        /* === HEADER ANTRIAN === */
        .dashboard-sidebar .card-header {
            background: linear-gradient(135deg, #38b6ff, #94e0ff);
            color: #0d1b2a;
            font-weight: 700;
            border: none;
            font-size: 1.2rem;
            letter-spacing: 0.5px;
        }

        /* === ICON CIRCLE === */
        .icon-circle {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #eef4ff;
            border-radius: 50%;
            width: 52px;
            height: 52px;
        }

        /* === VALUE BESAR (Nomor Antrian) === */
        .dashboard-sidebar .nomor-antrian {
            font-size: 3rem;
            font-weight: bold;
            color: #1b1e3a;
        }

        /* === SUB INFO === */
        .dashboard-sidebar .info-sub {
            font-size: 1rem;
            font-weight: 500;
            color: #444;
        }

        /* === BADGE & VALUE === */
        .dashboard-sidebar .stat-value {
            font-size: 1.4rem;
            font-weight: 700;
            color: #0d6efd;
        }

        /* === CHART CARD === */
        .dashboard-sidebar .card canvas {
            max-width: 100%;
            height: auto;
        }

        /* === HOVER-LIFT === */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.08);
        }

        /* === RESPONSIVE PADDING === */
        .dashboard-sidebar .card-body {
            padding: 1.5rem;
        }

        @media (max-width: 768px) {
            .dashboard-sidebar .card {
                border-radius: 1rem;
            }

            .dashboard-sidebar .nomor-antrian {
                font-size: 2.2rem;
            }

            .dashboard-sidebar .card-body {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm px-2 py-2">
        <div class="container-fluid">
            <a class="navbar-brand d-flex align-items-center text-white fw-bold" href="#">
                <img src="{{ asset('images/medikit.jpg') }}" alt="Logo" width="30" height="30"
                    class="me-2 rounded-circle">
                Dashboard Admin
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Link Navigasi -->
                    @php
                        $routes = [
                            ['indikasi.index', 'bi bi-heart-pulse', 'Layanan'],
                            ['sesi.index', 'bi bi-clock', 'Sesi'],
                            ['ruangan.index', 'bi bi-building', 'Ruangan'],
                            ['dokter.index', 'bi bi-prescription2', 'Dokter'],
                            ['pasien.index', 'bi bi-person-vcard', 'Pasien'],
                            ['kunjungan.index', 'bi-calendar-check', 'Kunjungan'],
                            ['laporan.index', 'bi bi-journal-medical', 'Laporan'],
                        ];
                    @endphp

                    @foreach ($routes as [$route, $icon, $label])
                        <li class="nav-item">
                            <a href="{{ route($route) }}"
                                class="nav-link {{ request()->routeIs(strtok($route, '.') . '.*') ? 'active' : '' }}">
                                <i class="{{ $icon }} me-1"></i> {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-outline-light rounded-pill px-3" type="button" id="dropdownUser"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle"></i> {{ Auth::user()->username }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end mt-2 shadow-sm" aria-labelledby="dropdownUser">
                        <li>
                            <a href="#" class="dropdown-item text-danger"
                                onclick="event.preventDefault(); document.getElementById('logout-form-admin').submit();">
                                <i class="bi bi-person-circle"></i> Logout
                            </a>
                        </li>
                    </ul>
                    <form id="logout-form-admin" action="{{ route('logout_admin') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script>
        // Aktifkan tooltip jika ada
        document.addEventListener('DOMContentLoaded', () => {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
        });
    </script>

    <!--RILOD-->
    <script>
        setTimeout(() => {
            location.reload(); // langsung reload tanpa transisi
        }, 300000); // 5 menit
    </script>

    <style>
        body {
            transition: opacity 0.3s ease-in-out;
        }
    </style>

</body>

</html>
