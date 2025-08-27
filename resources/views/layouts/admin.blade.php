<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    @php
        $segment = Request::segment(2) ?? 'dashboard';
        $pageTitle = ucfirst($segment);
    @endphp

    <title>{{ $pageTitle }} - Admin Klinik Sehat</title>

    @include('layouts.partials.cdn.admincss')
    @vite(['resources/css/Utama_Dashboard_Admin.css'])
</head>

<body>
    @if (Auth::check() && Auth::user()->role == 'admin')
        <div class="d-flex" id="wrapper">

            {{-- SIDEBAR --}}
            @include('layouts.partials.sidebar')

            <div id="mainContent" class="content flex-grow-1">

                <nav class="navbar navbar-expand-lg medical-navbar mb-4 rounded-3 shadow-sm px-4 py-2">
                    <div class="container-fluid px-0 align-items-center d-flex justify-content-between">

                        <div class="d-flex align-items-center">
                            <button id="toggleSidebar" class="btn btn-primary toggle-btn me-3"
                                aria-label="Toggle Navigation">
                                <i class="fas fa-bars"></i>
                            </button>

                            <ol
                                class="breadcrumb bg-light px-3 py-2 rounded-pill d-flex align-items-center mb-0 d-none d-sm-flex">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/admin/dashboard') }}" class="text-decoration-none text-primary">
                                        <i class="bi bi-house-fill me-1"></i> Admin
                                    </a>
                                </li>
                                <li class="breadcrumb-item active fw-bold" aria-current="page">
                                    {{ $pageTitle }}
                                </li>
                            </ol>
                        </div>

                        <span id="liveTime"
                            class="badge fs-6 text-bg-primary d-none d-md-inline-block py-2 px-3">--:--</span>

                        <div class="d-flex align-items-center">

                            <div class="dropdown me-3 d-none d-sm-block">
                                <button class="btn btn-sm btn-light dropdown-toggle border" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    🌐 {{ strtoupper(app()->getLocale()) }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'id') }}">🇮🇩
                                            Indonesia</a></li>
                                    <li><a class="dropdown-item" href="{{ route('lang.switch', 'en') }}">🇬🇧
                                            English</a></li>
                                </ul>
                            </div>

                            <button class="notification-btn position-relative btn btn-light me-3"
                                aria-label="Notifications">
                                <i class="bi bi-bell fs-5"></i>
                                @if (isset($jumlah_notifikasi) && $jumlah_notifikasi > 0)
                                    <span
                                        class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                        {{ $jumlah_notifikasi }}
                                    </span>
                                @endif
                            </button>

                            <div class="dropdown">
                                <button
                                    class="btn btn-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                                    id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false"
                                    style="width: 40px; height: 40px;" aria-label="User Menu">
                                    <i class="bi bi-person-circle fs-5"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end mt-2 shadow rounded border-0"
                                    aria-labelledby="dropdownUser">
                                    <li class="px-3 py-2 border-bottom">
                                        <div class="fw-semibold text-dark">{{ Auth::user()->username }}</div>
                                        <div class="text-muted small">{{ ucfirst(Auth::user()->role) }}</div>
                                    </li>
                                    <li><a class="dropdown-item" href="#"><i
                                                class="bi bi-person-fill-gear me-2"></i> Profil Saya</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="{{ route('logout_admin') }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item text-danger fw-semibold">
                                                <i class="bi bi-box-arrow-right me-2"></i> {{ __('messages.Keluar') }}
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <div class="p-3 p-lg-4">
                    @yield('dashboard')
                </div>

            </div>
        </div>
    @endif

    @include('layouts.partials.cdn.adminjs')
    @vite(['resources/js/Utama_Dashboard_Admin.js'])
</body>

</html>
