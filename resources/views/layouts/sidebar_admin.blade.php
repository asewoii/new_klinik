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
            <a href="{{ route('indikasi.index') }}"
                class="nav-link {{ request()->routeIs('indikasi.*') ? 'active' : '' }}">
                <i class="fa fa-heartbeat"></i>
                <span>Keluhan</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('dokter.index') }}" class="nav-link {{ request()->routeIs('dokter.*') ? 'active' : '' }}">
                <i class="fa fa-user-md"></i>
                <span>Spesialis</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('sesi.index') }}" class="nav-link {{ request()->routeIs('sesi.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check-fill"></i>
                <span>Sesi</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('pasien.index') }}" class="nav-link {{ request()->routeIs('pasien.*') ? 'active' : '' }}">
                <i class="bi bi-person-vcard-fill"></i>
                <span>Pasien</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('kunjungan.index') }}"
                class="nav-link {{ request()->routeIs('kunjungan.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i>
                <span>Kunjungan</span>
            </a>
        </li>
    </ul>
</div>
