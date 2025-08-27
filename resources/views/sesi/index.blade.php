@extends('layouts.nav_admin')
@section('title', 'Daftar Sesi')
@section('content')

    @if (session('success'))
        <div class="notification notification-success" id="notification-success">
            <i class="bi bi-clipboard-check-fill"></i>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="notification notification-error" id="notification-error">
            <i class="bi bi-exclamation-octagon-fill"></i>
            {{ session('error') }}
        </div>
    @endif

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
                    @if (isset($jumlah_notifikasi) && $jumlah_notifikasi > 0)
                        <span class="notification-badge">{{ $jumlah_notifikasi }}</span>
                    @endif
                </button>

                <!-- Dropdown Admin -->
                <div class="dropdown">
                    <button class="btn btn-light rounded-circle d-flex align-items-center justify-content-center shadow-sm"
                        id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false" style="width: 40px; height: 40px;"
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
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout_admin') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i> {{ __('messages.Keluar') }}
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4" id="sesi-table-container">
        <!-- Tabel Data -->
        <form method="POST" action="{{ route('sesi.select_delete') }}" id="select-delete-form">
            @csrf
            @method('DELETE')

            <!-- Header & Tombol Aksi -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <span id="liveTime" class="badge bg-info-subtle text-dark fs-6">--:--</span>
                <div class="mt-2 mt-md-0">
                    <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal"
                        data-bs-target="#modalTambahSesi">
                        <i class="bi bi-plus-circle me-1"></i> {{ __('messages.Tambah_Sesi') }}
                    </button>
                    <button type="submit" class="btn btn-sm btn-danger btn-trigger-delete" data-form="#select-delete-form"
                        data-type="sesi">
                        <i class="bi bi-trash me-1"></i> {{ __('messages.Hapus_Data') }}
                    </button>
                </div>
            </div>

            <!-- Tabel -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-list-ul me-2"></i>
                        {{ __('messages.Daftar_Sesi') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="overflow-x: auto;">
                        <table id="table"
                            class="custom-table table table-bordered table-hover table-striped align-middle text-center"
                            style="width: 100%;">
                            <thead class="table-primary">
                                <tr class="text-start">
                                    <th><input type="checkbox" id="select-all" data-type="sesi"></th>
                                    <th>{{ __('messages.Opsi') }}</th>
                                    <th>{{ __('messages.No') }}</th>
                                    <th>{{ __('messages.Nama_Sesi') }}</th>
                                    <th>{{ __('messages.Jam_Praktek') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sesiList as $item)
                                    <tr class="text-start">
                                        <td>
                                            <input type="checkbox" name="selected_sesi[]" value="{{ $item->Id_Sesi }}">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning me-2 btn-edit-sesi"
                                                data-id="{{ $item->Id_Sesi }}" data-nama="{{ $item->Nama_Sesi }}"
                                                data-mulai="{{ \Carbon\Carbon::parse($item->Mulai_Sesi)->format('H:i') }}"
                                                data-selesai="{{ \Carbon\Carbon::parse($item->Selesai_Sesi)->format('H:i') }}"
                                                data-bs-toggle="modal" data-bs-target="#modalEditSesi">
                                                <i class="bi bi-pencil-square me-1"></i>
                                            </button>
                                        </td>

                                        <td>{{ $loop->iteration }}</td>

                                        <td class="fw-semibold">
                                            <a href="#" data-bs-toggle="modal"
                                                data-bs-target="#modalNamaSesi{{ $item->Id_Sesi }}">
                                                @if (Lang::has('messages.' . $item->Nama_Sesi))
                                                    {{ __('messages.' . $item->Nama_Sesi) }}
                                                @else
                                                    <span
                                                        data-translate="{{ $item->Nama_Sesi }}">{{ $item->Nama_Sesi }}</span>
                                                @endif
                                            </a>
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item->Mulai_Sesi)->format('H:i A') }} -
                                            {{ \Carbon\Carbon::parse($item->Selesai_Sesi)->format('H:i A') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="{{ isset($data[0]) ? count($data[0]->getAttributes()) + 2 : 6 }}"
                                            class="text-center text-muted py-4">
                                            Tidak ada data ditemukan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal Tambah Sesi -->
    <div class="modal fade" id="modalTambahSesi" tabindex="-1" aria-labelledby="modalTambahSesiLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content rounded-3 shadow">
                <form action="{{ route('sesi.store') }}" method="POST">
                    @csrf

                    <!-- Header -->
                    <div class="modal-header bg-primary text-white rounded-top">
                        <h5 class="modal-title fw-bold" id="modalTambahSesiLabel">
                            <i class="bi bi-plus-circle me-2"></i> Tambah Sesi
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Tutup"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body py-4 px-4">
                        <div class="mb-3">
                            <div class="col-md-12">
                                <label for="nama_sesi" class="form-label">Nama Sesi</label>
                                <div class="position-relative">
                                    <input type="text" name="Nama_Sesi" id="nama_sesi" class="form-control"
                                        placeholder="Contoh: Pagi / Siang" required>
                                    <div id="loader-validasi-sesi"
                                        class="spinner-border text-primary position-absolute end-0 top-0 mt-2 me-2 d-none"
                                        role="status" style="width: 1rem; height: 1rem;">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div class="invalid-feedback d-block"></div>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="mulai_sesi" class="form-label">Jam Mulai</label>
                                <input type="time" name="Mulai_Sesi" id="mulai_sesi" class="form-control" required>
                                <div class="invalid-feedback d-block"></div>
                            </div>

                            <div class="col-md-6">
                                <label for="selesai_sesi" class="form-label">Jam Selesai</label>
                                <input type="time" name="Selesai_Sesi" id="selesai_sesi" class="form-control"
                                    required>
                                <div class="invalid-feedback d-block"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer justify-content-end">
                        <button type="submit" class="btn btn-success" id="btn_simpan_sesi" disabled>
                            <i class="bi bi-save-fill me-1"></i> Simpan
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Sesi -->
    <div class="modal fade" id="modalEditSesi" tabindex="-1" aria-labelledby="modalEditSesiLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-3 shadow">
                <form id="formEditSesi" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="id_sesi_edit" name="Id_Sesi">

                    <!-- Header -->
                    <div class="modal-header bg-warning text-dark rounded-top">
                        <h5 class="modal-title fw-bold" id="modalEditSesiLabel">
                            <i class="bi bi-pencil-square me-2"></i>Edit Sesi
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body py-4 px-4">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="nama_sesi_edit" class="form-label">Nama Sesi</label>
                                <input type="text" id="nama_sesi_edit" name="Nama_Sesi" class="form-control"
                                    value="{{ old('Nama_Sesi') }}" required>
                                <div class="invalid-feedback d-block" id="nama_sesi_edit_feedback"></div>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label for="mulai_sesi_edit" class="form-label">Mulai Sesi</label>
                                <input type="time" id="mulai_sesi_edit" name="Mulai_Sesi" class="form-control"
                                    value="{{ old('Mulai_Sesi') }}" required>
                                <div class="invalid-feedback d-block" id="mulai_sesi_edit_feedback"></div>
                            </div>

                            <div class="col-md-6">
                                <label for="selesai_sesi_edit" class="form-label">Selesai Sesi</label>
                                <input type="time" id="selesai_sesi_edit" name="Selesai_Sesi" class="form-control"
                                    value="{{ old('Nama_Sesi') }}" required>
                                <div class="invalid-feedback d-block" id="selesai_sesi_edit_feedback"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer justify-content-end">
                        <button type="submit" class="btn btn-success" id="btn_update_sesi">
                            <i class="bi bi-save-fill me-1"></i> Simpan Perubahan
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <!-- Modal Detail Sesi -->
    @foreach ($sesiList as $item)
        @if (!empty($item->Id_Sesi))
            <div class="modal fade" id="modalNamaSesi{{ $item->Id_Sesi }}" tabindex="-1"
                aria-labelledby="label{{ $item->Id_Sesi }}" aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="label{{ $item->Id_Sesi }}">Detail Sesi: {{ $item->Nama_Sesi }}
                            </h5>
                            <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-person-badge"></i> Daftar
                                    Dokter/Sesi</h6>
                                <span class="live_Time badge bg-info-subtle text-dark fs-6">--:--</span>
                                <a href="{{ route('dokter.jadwal_harian') }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-calendar2-week"></i> Info Jadwal Lengkap
                                </a>
                            </div>

                            <div class="d-flex gap-3 flex-wrap mb-4">
                                <div class="info-box">
                                    <div class="text-muted small">Total Dokter</div>
                                    <div class="fs-5 fw-bold text-dark">{{ $item->total_dokter }}</div>
                                </div>
                                <div class="info-box">
                                    <div class="text-muted small">Total Jadwal</div>
                                    <div class="fs-5 fw-bold text-dark">{{ $item->total_jadwal_dokter }}</div>
                                </div>
                                <div class="info-box">
                                    <div class="text-muted small">Pasien Hari Ini</div>
                                    <div class="fs-5 fw-bold text-dark">{{ $item->total_pasien_hari_ini }}</div>
                                </div>
                            </div>

                            <!-- Dokter yang Mengisi Sesi Ini -->
                            <h6 class="mb-2 text-primary">Dokter yang Mengisi Sesi Ini:</h6>
                            <ul class="list-group mb-4">
                                @forelse ($item->dokterList as $dok)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <strong>{{ $dok->Nama_Dokter }}</strong><br>
                                            <small>Spesialis: {{ $dok->Spesialis ?? '-' }}</small>
                                        </div>
                                        <span class="badge bg-secondary">ID: {{ $dok->Id_Dokter }}</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-muted">Tidak ada dokter untuk sesi ini.</li>
                                @endforelse
                            </ul>


                            <!-- Pasien Hari Ini -->
                            <h6 class="mb-2 text-success">Pasien Hari Ini yang Menggunakan Sesi Ini:</h6>
                            @php
                                $pasienHariIni = $item->kunjungan ?? collect();
                            @endphp

                            @if ($pasienHariIni->isNotEmpty())
                                <div class="table-responsive">
                                    <table class="table table-sm table-striped align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Nama Pasien</th>
                                                <th>Dokter</th>
                                                <th>Jadwal</th>
                                                <th>Keluhan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pasienHariIni as $kunjungan)
                                                <tr>
                                                    <td>{{ $kunjungan->Nama_Pasien }}</td>
                                                    <td>{{ $kunjungan->dokter->Nama_Dokter ?? '-' }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($kunjungan->Jadwal)->format('d M Y H:i') }}
                                                    </td>
                                                    <td>{{ $kunjungan->Keluhan ?? '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    Tidak ada pasien yang terdaftar di sesi ini hari ini.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
@endsection
