<div class="anti_salin sidebar shadow" id="sidebar">
    <a class="navbar-brand text-decoration-none" href="#">
        <div class="brand-icon">
            <i class="bi bi-hospital-fill"></i>
        </div>
        <div class="brand-text">
            <span class="brand-main">MediCare</span>
            <span class="brand-sub">Pasien</span>
        </div>
    </a>

    <div class="garisss"></div>

    <ul class="nav navbar-nav mx-auto">
        <li class="nav-item">
            <a href="{{ route('kunjungan.index') }}" class="nav-link {{ request()->routeIs('kunjungan.*') ? 'active' : '' }}">
                <i class="bi bi-calendar-check"></i>
                <span>Data Kunjungan</span>
            </a>
        </li>
    </ul>
</div>