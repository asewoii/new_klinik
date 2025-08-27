<nav class="navbar navbar-expand-lg medical-navbar mb-2 rounded shadow-sm px-3">
    <div class="container-fluid px-0 align-items-center d-flex justify-content-between">

        <!-- Tombol Sidebar -->
        <div class="d-flex align-items-center">
            <button id="toggleSidebarBtn" class="btn btn-primary me-2">
                <i class="bi bi-list"></i>
            </button>

            <!-- Breadcrumb -->
            <ol class="breadcrumb bg-light px-3 py-2 rounded d-flex align-items-center mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ url('/admin') }}" class="text-decoration-none text-primary">
                        <i class="bi bi-house-fill"></i>
                        {{ __('messages.Dasbor') }}
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    {{ __('messages.Sesi') }}
                </li>
            </ol>
        </div>

        <div class="d-flex align-items-center">

        <div class="dropdown me-2">
            <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                🌐 {{ app()->getLocale() }}
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item " href="{{ route('lang.switch', 'id') }}">🇮🇩 Indonesia</a></li>
                <li><a class="dropdown-item " href="{{ route('lang.switch', 'en') }}">🇬🇧 English</a></li>
            </ul>
        </div>

            <!-- Notifikasi -->
            <button class="anti_salin notification-btn position-relative btn btn-light me-3">
                <i class="bi bi-bell fs-5"></i>
                @if(isset($jumlah_notifikasi) && $jumlah_notifikasi > 0)
                    <span class="notification-badge">{{ $jumlah_notifikasi }}</span>
                @endif
            </button>

            <!-- Dropdown Admin -->
            <div class="dropdown">
                <button class="btn btn-light rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                    id="dropdownUser"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    style="width: 40px; height: 40px;"
                    aria-label="User Menu">
                    <i class="bi bi-person-circle fs-5"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end mt-2 shadow rounded border-0" aria-labelledby="dropdownUser">
                    <li class="px-3 py-2">
                        <div class="fw-semibold text-dark">
                            {{ Auth::user()->username }}
                        </div>
                        <div class="text-muted small">
                            {{ ucfirst(Auth::user()->role) }}
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout_admin') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-box-arrow-right me-2"></i> @transauto('Keluar')
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>