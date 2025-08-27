@extends('layouts.nav_admin')
@section('title', 'Daftar Ruangan')
@section('content')

@if(session('success'))
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
                    {{ __('messages.Ruangan') }}
                </li>
            </ol>
        </div>

        <div class="d-flex align-items-center">

        <div class="dropdown me-2">
            <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                🌐 {{ app()->getLocale() }}
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item " href="{{ route('lang.switch', 'id') }}">🇮🇩 {{ __('messages.Indonesia') }}</a></li>
                <li><a class="dropdown-item " href="{{ route('lang.switch', 'en') }}">🇬🇧 {{ __('messages.English') }}</a></li>
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
                                <i class="bi bi-box-arrow-right me-2"></i> {{ __('messages.Keluar') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="container-fluid mt-4" id="ruangan-table-container">  
    <!-- Tabel Data -->
    <form method="POST" action="{{ route('ruangan.select_delete') }}" id="select-delete-form">
        @csrf
        @method('DELETE') 

        <!-- Header & Tombol Aksi -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <span id="liveTime" class="badge bg-info-subtle text-dark fs-6">--:--</span>
            <div class="mt-2 mt-md-0">
                <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modalTambahRuangan">
                    <i class="bi bi-plus-circle me-1"></i> {{ __('messages.Tambah_Ruangan') }}
                </button>
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data yang dipilih?')">
                    <i class="bi bi-trash me-1"></i> {{ __('messages.Hapus_Data') }}
                </button>
            </div>
        </div>

        <!-- Tabel -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    {{ __('messages.Daftar_Ruangan') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="table" class="custom-table table table-bordered table-hover table-striped align-middle text-center" style="width: 100%;">
                        <thead class="table-primary">
                            <tr class="text-start">
                                <th><input type="checkbox" id="select-all" data-type="ruangan"></th>
                                <th>{{ __('messages.Opsi') }}</th>
                                <th>{{ __('messages.No') }}</th>
                                <th>{{ __('messages.Nama_Ruangan') }}</th>
                                <th>{{ __('messages.Jenis_Ruangan') }}</th>
                                <th>{{ __('messages.Lantai') }}</th>
                                <th>{{ __('messages.Status') }}</th>
                                <th>{{ __('messages.Keterangan') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($ruangan as $item)
                            <tr class="text-start">
                                <td>
                                    <input type="checkbox" name="selected_ruangan[]" value="{{ $item->Id_Ruangan }}">
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-warning btn-edit-ruangan"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEditRuangan"
                                        data-id="{{ $item->Id_Ruangan }}"
                                        data-nama="{{ $item->Nama_Ruangan }}"
                                        data-jenis="{{ $item->Jenis_Ruangan }}"
                                        data-lantai="{{ $item->Lantai }}"
                                        data-status="{{ $item->Status }}"
                                        data-keterangan="{{ $item->Keterangan }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                </td>
                                <td>{{ $loop->iteration }}</td>

                                <td class="fw-semibold">
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalNamaRuangan{{ $item->Id_Ruangan }}">
                                        @if (Lang::has('messages.' . $item->Nama_Ruangan))
                                            {{ __('messages.' . $item->Nama_Ruangan) }}
                                        @else
                                            <span data-translate="{{ $item->Nama_Ruangan }}">{{ $item->Nama_Ruangan }}</span>
                                        @endif
                                    </a>
                                </td>

                                <td>{{ $item->Jenis_Ruangan }}</td>
                                <td>{{ $item->Lantai }}</td>
                                <td>{{ ucfirst($item->Status) }}</td>
                                <td>{{ $item->Keterangan }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ isset($data[0]) ? count($data[0]->getAttributes()) + 2 : 6 }}" class="text-center text-muted py-4">
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

<!-- Modal Tambah Ruangan -->
<div class="modal fade" id="modalTambahRuangan" tabindex="-1" aria-labelledby="modalTambahRuanganLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-3 shadow">
            <form action="{{ route('ruangan.store') }}" method="POST">
                @csrf

                <!-- Header -->
                <div class="modal-header bg-primary text-white rounded-top">
                    <h5 class="modal-title fw-bold" id="modalTambahRuanganLabel">
                        <i class="bi bi-plus-circle me-2"></i> {{ __('messages.Tambah_Ruangan') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <!-- Body -->
                <div class="modal-body py-4 px-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nama_ruangan" class="form-label">{{ __('messages.Nama_Ruangan') }}</label>
                            <input type="text" name="Nama_Ruangan" id="nama_ruangan" class="form-control" data-translate="Contoh: R004 / R003" placeholder="Contoh: R004 / R003" required>
                            <div class="invalid-feedback d-block" id="nama_ruangan_feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="Jenis_Ruangan" class="form-label">{{ __('messages.Jenis_Ruangan') }}</label>
                            <input type="text" name="Jenis_Ruangan" id="jenis_ruangan" class="form-control" value="{{ old('Jenis_Ruangan') }}" placeholder="Contoh Layanan / Pijat / Layanan Gigi" required>
                            <div class="invalid-feedback d-block" id="jenis_ruangan_feedback"></div>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label for="Lantai" class="form-label">{{ __('messages.Lantai') }}</label>
                            <input type="number" name="Lantai" id="lantai" class="form-control" value="{{ old('Lantai') }}" placeholder="Lantai" required>
                            <div class="invalid-feedback d-block" id="lantai_feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="Status" class="form-label">{{ __('messages.Status') }}</label>
                            <select type="number" name="Status" id="status" class="form-select" required>
                                <option value="" disabled selected>--- {{ __('messages.Pilih_Status') }} ---</option>
                                <option value="aktif">{{ __('messages.Aktif') }}</option>
                                <option value="nonaktif">{{ __('messages.Nonaktif') }}</option>
                                <option value="dalam perbaikan">{{ __('messages.Dalam_Perbaikan') }}</option>
                            </select>
                            <div class="invalid-feedback d-block" id="status_feedback"></div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="Keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" name="Keterangan" placeholder="Tulis keterangan ruangan ini" rows="3">{{ old('Keterangan') }}</textarea>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer justify-content-end">
                    <button type="submit" class="btn btn-success" id="btn_simpan_ruangan" disabled>
                        <i class="bi bi-save-fill me-1"></i> Simpan
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Edit Ruangan -->
<div class="modal fade" id="modalEditRuangan" tabindex="-1" aria-labelledby="modalEditRuanganLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-3 shadow">
            <form id="formEditRuangan" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="id_Ruangan_edit" name="Id_Ruangan">

                <!-- Header -->
                <div class="modal-header bg-warning text-dark rounded-top">
                    <h5 class="modal-title fw-bold" id="modalEditRuanganLabel">
                        <i class="bi bi-pencil-square me-2"></i>Edit Ruangan
                    </h5>
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <!-- Body -->
                <div class="modal-body py-4 px-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nama_ruangan_edit" class="form-label">Nama Ruangan</label>
                            <input type="text" id="nama_ruangan_edit" name="Nama_Ruangan" class="form-control" value="{{ old('Nama_Ruangan') }}" required>
                            <div class="invalid-feedback d-block" id="nama_ruangan_edit_feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="jenis_ruangan_edit" class="form-label">Jenis Ruangan</label>
                            <input type="text" id="jenis_ruangan_edit" name="Jenis_Ruangan" class="form-control" value="{{ old('Jenis_Ruangan') }}" required>
                            <div class="invalid-feedback d-block" id="jenis_ruangan_edit_feedback"></div>
                        </div>
                    </div>

                    <div class="row g-3 mt-3">
                        <div class="col-md-6">
                            <label for="lantai_ruangan_edit" class="form-label">Lantai</label>
                            <input type="number" id="lantai_ruangan_edit" name="Lantai" class="form-control" value="{{ old('Lantai') }}" required>
                            <div class="invalid-feedback d-block" id="lantai_ruangan_edit_feedback"></div>
                        </div>

                        <div class="col-md-6">
                            <label for="Status_ruangan_edit" class="form-label">Status</label>
                            <select class="form-select" id="Status_ruangan_edit" name="Status" required>
                                <option value="" disabled selected>--- Pilih Status ---</option>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                                <option value="dalam perbaikan">Dalam Perbaikan</option>
                            </select>
                            <div class="invalid-feedback d-block" id="status_ruangan_edit_feedback"></div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <label for="Keterangan_ruangan_edit" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="Keterangan_ruangan_edit" name="Keterangan" placeholder="Tulis keterangan ruangan ini" rows="3">{{ old('Keterangan') }}</textarea>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="modal-footer justify-content-end">
                    <button type="submit" class="btn btn-success" id="btn_update_ruangan">
                        <i class="bi bi-save-fill me-1"></i> Simpan Perubahan
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Jenis Ruangan -->
@foreach ($ruangan as $item)
    <div class="modal fade" id="modalNamaRuangan{{ $item->Id_Ruangan }}" tabindex="-1" aria-labelledby="modalLabel{{ $item->Id_Ruangan }}" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Ruangan: {{ $item->Nama_Ruangan }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">

                    <!-- === List === -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-person-badge"></i> Daftar Dokter</h6>
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

                    @php
                        $doktersFiltered = $item->dokterList ?? collect();
                    @endphp

                    <!-- === DOKTER === -->
                    @if ($doktersFiltered->isEmpty())
                        <div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Belum ada dokter yang terkait dengan Ruangan ini.</div>
                    @else
                        <div class="table-responsive mb-4" style="overflow-x: auto;">
                            <table class="custom-table table table-bordered table-sm datatable-dokter" id="dokterTable-{{ $item->Id_Ruangan }}" style="width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Dokter</th>
                                        <th>Ruangan</th>
                                        <th>Spesialis</th>
                                        <th>Hari / Jam Praktek</th>
                                        <th>No Telepon</th>
                                        <th>Terpakai / Kuota Max</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($doktersFiltered as $dokter)
                                        @php
                                            $jadwalList = $dokter->jadwalHariIni ?? [];
                                            $hasJadwal = !empty($jadwalList);
                                        @endphp

                                        @if ($hasJadwal)
                                            @foreach ($jadwalList as $j)
                                            <pre>{{ print_r($doktersFiltered->toArray(), true) }}</pre>
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-info-subtle text-dark">
                                                            {{ $dokter->Nama_Dokter }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="badge bg-primary-subtle text-dark">
                                                            {{ $j['ruang'] ?? '-' }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="badge bg-secondary-subtle text-dark">
                                                            {{ $dokter->Spesialis }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="badge bg-warning-subtle text-dark">
                                                            {{ ucfirst($today) }} {{ $j['start'] ?? '-' }} - {{ $j['end'] ?? '-' }} {{ $dokter->jamKategori }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        @if ($dokter->wa_link)
                                                            <a href="{{ $dokter->wa_link }}" target="_blank" class="badge bg-success-subtle text-dark text-decoration-none">
                                                                {{ $dokter->No_Telp }}
                                                            </a>
                                                        @else
                                                            <span class="badge bg-danger-subtle text-dark">-</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <span class="badge bg-light text-dark border">
                                                            {{ $j['terpakai'] ?? '0' }} / {{ $j['kuota'] ?? '0' }}
                                                        </span>
                                                    </td>

                                                    <td>
                                                        <span class="badge {{ ($j['status'] ?? '') === 'Tersedia' ? 'bg-success' : 'bg-danger' }}">
                                                            {{ $j['status'] ?? '-' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- === PASIEN === -->
                    @php
                        $pasienList = $item->pasienList ?? collect();
                    @endphp

                    @if ($pasienList->isEmpty())
                        <div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Belum ada pasien yang terkait dengan Ruangan ini.</div>
                    @else
                    <div class="table-responsive mb-4" style="overflow-x: auto;">
                            <table class="custom-table table table-bordered table-sm datatable-dokter" id="dokterTable-{{ $item->Id_Ruangan }}" style="width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Pasien</th>
                                        <th>Ruangan</th>
                                        <th>Dokter Pemeriksa</th>
                                        <th>No Antrian</th>
                                    </tr>
                                </thead>
                    
                        
                            <tbody>
                                @foreach ($pasienList as $pasien)
                                        <tr>
                                            <td>
                                                <span class="badge badge-saya bg-info-subtle text-dark">
                                                    {{ $pasien->Nama_Pasien }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-ruangan bg-primary-subtle text-dark">
                                                    {{ $pasien->Nama_Ruangan }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-dokter bg-success-subtle text-dark">
                                                    {{ $pasien->Nama_Dokter }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-nomor bg-warning-subtle text-dark">
                                                    {{ $pasien->Nomor_Urut ?? '-' }}
                                                </span>
                                            </td>
                                        </tr>

                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

@endsection
