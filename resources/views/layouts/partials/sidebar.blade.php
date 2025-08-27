<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay"></div>

<nav id="sidebar" class="sidebar">
    <a class="navbar-brand py-3 px-4 d-flex align-items-center text-decoration-none" href="{{ route('admin') }}">
        <div class="brand-icon me-2">
            <i class="bi bi-hospital-fill fs-3 text-primary"></i>
        </div>
        <div class="brand-text d-flex flex-column">
            <span class="brand-atas fw-bold fs-5 text-dark">MediCare</span>
            <span class="brand-bawah text-muted">Admin Dashboard</span>
        </div>
    </a>

    <ul class="nav_sidebar nav flex-column py-3 px-3">
        {{-- DASHBOARD --}}
        <li class="nav-item">
            <a href="{{ route('admin') }}" class="nav-link {{ request()->routeIs('admin') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high fa-fw me-2"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- Layanan --}}
        <li class="nav-item">
            <a href="{{ route('indikasi.index') }}"
                class="nav-link {{ request()->routeIs('indikasi.*') ? 'active' : '' }}">
                <i class="fa-solid fa-notes-medical fa-fw me-2"></i>
                <span>Layanan</span>
            </a>
        </li>

        {{-- Sesi --}}
        <li class="nav-item">
            <a href="{{ route('sesi.index') }}" class="nav-link {{ request()->routeIs('sesi.*') ? 'active' : '' }}">
                <i class="fa-solid fa-cloud-sun fa-fw me-2"></i>
                <span>Sesi</span>
            </a>
        </li>

        {{-- Ruangan --}}
        <li class="nav-item">
            <a href="{{ route('ruangan.index') }}"
                class="nav-link {{ request()->routeIs('ruangan.*') ? 'active' : '' }}">
                <i class="fa-solid fa-stethoscope fa-fw me-2"></i>
                <span>Ruangan</span>
            </a>
        </li>

        {{-- Dokter --}}
        @php
            $isDokterActive = request()->routeIs(['dokter.*', 'admin.pemeriksaan.periksa', 'pemeriksaan.index']);
        @endphp
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center collapsed {{ $isDokterActive ? 'menu-active' : '' }}"
                data-bs-toggle="collapse" href="#menuDokter" role="button"
                aria-expanded="{{ $isDokterActive ? 'true' : 'false' }}" aria-controls="menuDokter">
                <i class="fa-solid fa-user-doctor fa-fw me-2"></i>
                <span>Dokter</span>
                <i class="fa-solid fa-chevron-down small chevron-icon"></i>
            </a>
        </li>

        <!-- Submenu (Dokter) -->
        <div class="collapse ps-3 {{ $isDokterActive ? 'show' : '' }}" id="menuDokter">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('dokter.index') }}"
                        class="nav-link {{ request()->routeIs('dokter.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-circle-dot fa-fw me-2 small"></i>
                        Data Dokter
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dokter.jadwal_harian') }}"
                        class="nav-link {{ request()->routeIs('dokter.jadwal_harian') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-days fa-fw me-2 small"></i>
                        Jadwal Dokter
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.pemeriksaan.periksa') }}"
                        class="nav-link {{ request()->routeIs('admin.pemeriksaan.periksa') ? 'active' : '' }}">
                        <i class="fa-solid fa-stethoscope fa-fw me-2 small"></i>
                        Pemeriksaan Pasien
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pemeriksaan.index') }}"
                        class="nav-link {{ request()->routeIs('pemeriksaan.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-file-medical fa-fw me-2 small"></i>
                        Hasil Pemeriksaan
                    </a>
                </li>
            </ul>
        </div>

        {{-- Pasien --}}
        <li class="nav-item">
            <a href="{{ route('pasien.index') }}"
                class="nav-link {{ request()->routeIs('pasien.*') ? 'active' : '' }}">
                <i class="fa-solid fa-hospital-user fa-fw me-2"></i>
                <span>Pasien</span>
            </a>
        </li>

        {{-- Kunjungan --}}
        @php
            $isKunjunganActive = request()->routeIs(['kunjungan.*', 'admin.kunjungan.hari_ini']);
        @endphp
        <li class="nav-item">
            <a class="nav-link d-flex justify-content-between align-items-center collapsed {{ $isKunjunganActive ? 'menu-active' : '' }}"
                data-bs-toggle="collapse" href="#menuKunjungan" role="button"
                aria-expanded="{{ $isKunjunganActive ? 'true' : 'false' }}" aria-controls="menuKunjungan">
                <i class="fa-solid fa-calendar fa-fw me-2"></i>
                <span>Kunjungan</span>
                <i class="fa-solid fa-chevron-down small chevron-icon"></i>
            </a>
        </li>

        <!-- Submenu (Kunjungan) -->
        <div class="collapse ps-3 {{ $isKunjunganActive ? 'show' : '' }}" id="menuKunjungan">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('kunjungan.index') }}"
                        class="nav-link {{ request()->routeIs('kunjungan.index') ? 'active' : '' }}">
                        <i class="fa-solid fa-clipboard-list fa-fw me-2 small"></i>
                        Semua Kunjungan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.kunjungan.hari_ini') }}"
                        class="nav-link {{ request()->routeIs('admin.kunjungan.hari_ini') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-day fa-fw me-2 small"></i>
                        Kunjungan Hari Ini
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('kunjungan.laporan') }}"
                        class="nav-link {{ request()->routeIs('kunjungan.laporan') ? 'active' : '' }}">
                        <i class="fa-solid fa-calendar-week fa-fw me-2 small"></i>
                        Laporan Kunjungan
                    </a>
                </li>
            </ul>
        </div>

        <hr class="dropdown-divider my-2 mx-3 border-secondary opacity-25">

        {{-- Laporan --}}
        <li class="nav-item">
            <a href="{{ route('laporan.index') }}"
                class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <i class="fa-solid fa-database fa-fw me-2"></i>
                <span>Laporan Data</span>
            </a>
        </li>

        {{-- Setting --}}
        <li class="nav-item">
            <a href="#" class="nav-link">
                <i class="fa-solid fa-gear fa-fw me-2"></i>
                <span>Setting</span>
            </a>
        </li>
    </ul>
</nav>
