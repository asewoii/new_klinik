@extends('layouts.nav_admin')
@section('title', 'Jenis Layanan')
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
                    {{ __('messages.Layanan') }}
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
                                <i class="bi bi-box-arrow-right me-2"></i> {{ __('messages.Keluar') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>


<div class="container-fluid mt-4" id="indikasi-table-container">  
    <!-- Tabel Data -->
    <form method="POST" action="{{ route('indikasi.select_delete') }}" id="select-delete-form">
        @csrf
        @method('DELETE') 

        <!-- Header & Tombol Aksi -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <span id="liveTime" class="badge bg-info-subtle text-dark fs-6">--:--</span>
            <div class="mt-2 mt-md-0">
                <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modalTambahIndikasi">
                    <i class="bi bi-plus-circle me-1"></i> {{ __('messages.Tambah_Layanan') }}
                </button>
                <button type="submit" class="btn btn-sm btn-danger btn-trigger-delete" data-form="#select-delete-form" data-type="layanan">
                    <i class="bi bi-trash me-1"></i> {{ __('messages.Hapus_Data') }}
                </button>
            </div>
        </div>

        <!-- Tabel -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    {{ __('messages.Daftar_Layanan') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="table" class="custom-table table table-bordered table-hover table-striped align-middle text-center" style="width: 100%;">
                        <thead class="table-primary small text-muted">
                            <tr class="text-start">
                                <th><input type="checkbox" id="select-all" data-type="layanan"></th>
                                <th>{{ __('messages.Opsi') }}</th>
                                <th>{{ __('messages.No') }}</th>
                                <th><span data-translate="Kode Layanan">{{ __('messages.Kode_Layanan') }}</span></th>
                                <th><span data-translate="Jenis Layanan">{{ __('messages.Jenis_Layanan') }}</span></th>
                                <th>{{ __('messages.Dibuat_Oleh') }}</th>
                                <th><span data-translate="Tanggal Dibuat">{{ __('messages.Tanggal_Dibuat') }}</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                            <tr class="text-start">
                                <td>
                                    <input type="checkbox" name="selected_layanan[]" value="{{ $item->Id_Layanan }}">
                                </td>
                                <td>
                                    <button type="button" 
                                        class="btn btn-sm btn-warning me-2 btn-edit-keluhan" 
                                        data-id="{{ $item->Id_Layanan }}" 
                                        data-nama="{{ $item->Nama_Layanan }}" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalEditKeluhan">
                                        <i class="bi bi-pencil-square me-1"></i>
                                    </button>
                                </td>
                                <td>{{ $loop->iteration }}</td>
                                <td class="fw-semibold">{{ $item->Id_Layanan }}</td>
                                <td>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#modalLayanan{{ $item->Id_Layanan }}">
                                        @if (Lang::has('messages.' . $item->Nama_Layanan))
                                            {{ __('messages.' . $item->Nama_Layanan) }}
                                        @else
                                            <span data-translate="{{ $item->Nama_Layanan }}">{{ $item->Nama_Layanan }}</span>
                                        @endif
                                    </a>
                                </td>
                                <td>{{ $item->Create_By }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->Create_Date)->translatedFormat('l, d F Y H:i A') }}</td>

                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ isset($data[0]) ? count($data[0]->getAttributes()) + 2 : 6 }}" class="text-center text-muted py-4">
                                    {{ __('messages.Data_Kosong') }}
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

<!-- Modal Edit Nama_Layanan -->
<div class="modal fade" id="modalEditKeluhan" tabindex="-1" aria-labelledby="modalEditKeluhanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content rounded-3 shadow">
            <form method="POST" id="formEditKeluhan">
                @csrf
                @method('PUT')

                <!-- Header -->
                <div class="modal-header bg-warning text-dark rounded-top">
                    <h5 class="modal-title fw-bold" id="modalEditKeluhanLabel">
                        <i class="bi bi-pencil-square me-2"></i>
                        {{ __('messages.Edit_Keluhan') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-black" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <!-- Body -->
                <div class="modal-body py-4 px-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="editKode" class="form-label">{{ __('messages.Kode_Layanan') }}</label>
                            <input type="text" id="editKode" name="Id_Layanan" class="form-control" readonly>
                        </div>

                        <div class="col-md-12">
                            <label for="editNama" class="form-label">{{ __('messages.Jenis_Layanan') }}</label>
                            <div class="position-relative">
                                <textarea id="editNama" name="Nama_Layanan" class="form-control pe-5" rows="4" required></textarea>
                                <div id="loader-validasi"
                                    class="spinner-border text-primary position-relative end-0 top-0 mt-2 me-2 d-none"
                                    role="status" style="width: 1rem; height: 1rem;">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                            <div class="invalid-feedback d-block"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="modal-footer justify-content-end">
                    <button type="submit" class="btn btn-success" id="btn_update_keluhan">
                        <i class="bi bi-save-fill me-1"></i> {{ __('messages.Simpan_Perubahan') }}
                    </button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Tambah Indikasi -->
<div class="modal fade" id="modalTambahIndikasi" tabindex="-1" aria-labelledby="modalTambahIndikasiLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content rounded-3 shadow">
            <form action="{{ route('indikasi.store') }}" method="POST">
                @csrf

                <!-- Header -->
                <div class="modal-header bg-primary text-white rounded-top">
                    <h5 class="modal-title fw-bold" id="modalTambahIndikasiLabel">
                        <i class="bi bi-plus-circle me-2"></i>{{ __('messages.Tambah_Poli') }} 
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <!-- Body -->
                <div class="modal-body py-4 px-4">
                    <div class="scroll_create_layanan" style="max-height: 400px; overflow-y: auto;">
                        <div id="input-keluhan-wrapper" class="row g-3 flex-wrap">
                            <div class="col-md-6 keluhan-input mb-3">
                                <div class="form-group position-relative">
                                    <label class="form-label fw-semibold">Jenis Layanan</label>

                                    <div class="position-relative">
                                        <input type="text" name="Nama_Layanan[]" class="form-control pe-5" placeholder="Contoh: Layanan Anak / Pijat Refleksi" required>

                                        <span class="position-absolute top-50 end-0 translate-middle-y me-3 feedback-icon">
                                            <span class="spinner-border spinner-border-sm text-secondary d-none loader-icon" role="status"></span>
                                        </span>
                                    </div>

                                    <button type="button" class="btn btn-outline-danger btn-sm mt-2 remove-keluhan">
                                        <i class="bi bi-trash-fill"></i> Hapus
                                    </button>

                                    <div class="invalid-feedback d-block"></div>
                                </div>
                            </div>
                            <div class="col-md-6 d-none">
                                <input type="text" class="form-control form-control-lg" placeholder="Keterangan / Catatan Opsional">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="btn_tambah_layanan">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Layanan Lagi
                    </button>
                    <div class="d-flex gap-2">
                        <button type="submit" id="btn_simpan_layanan" class="btn btn-success">
                            <i class="bi bi-save-fill me-1"></i> Simpan
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Notifikasi -->
<div class="modal fade" id="modal_new_notifikasi" tabindex="-1" aria-labelledby="modalNotifikasiLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNotifikasiLabel">📢 Notifikasi Layanan Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body" id="notifikasi_list">
        <p class="text-muted">Memuat notifikasi...</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Jenis Poli -->
@foreach ($data as $item)
    <div class="modal fade" id="modalLayanan{{ $item->Id_Layanan }}" tabindex="-1" aria-labelledby="modalLabel{{ $item->Id_Layanan }}" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="bi bi-hospital"></i> 
                        {{ __('messages.Info_Layanan') }} : <span data-translate="{{ $item->Nama_Layanan }}">{{ $item->Nama_Layanan }}</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">

                    <!-- === DOKTER === -->
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

                    @if ($item->dokters->isEmpty())
                        <div class="alert alert-warning"><i class="bi bi-exclamation-triangle"></i> Belum ada dokter yang terkait dengan layanan ini.</div>
                    @else
                        <div class="table-responsive mb-4" style="overflow-x: auto;">
                            <table class="custom-table table table-bordered table-sm datatable-dokter" id="table-dokter-{{ $item->Id_Layanan }}" style="width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nama Dokter</th>
                                        <th>Hari</th>
                                        <th>Ruangan</th>
                                        <th>Jam Praktek</th>
                                        <th>Kuota/Max</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($item->dokters as $dokter)
                                        @foreach ($dokter->jadwal_hari_ini as $jadwal)
                                            <tr>
                                                <td>
                                                    <span class="badge badge-saya bg-info-subtle text-dark ">
                                                        {{ $dokter->Nama_Dokter }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge-status badge-tersedia">
                                                        {{ $jadwal['hari'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge-status badge-tersedia">
                                                        {{ $jadwal['ruang'] }}
                                                    </span
                                                    >
                                                </td>
                                                <td>
                                                    <span class="badge-status badge-tersedia">
                                                        {{ $jadwal['start'] }} - {{ $jadwal['end'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge-status badge-tersedia">
                                                        {{ $jadwal['kuota terpakai'] }} / {{ $jadwal['kuota max'] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge
                                                        @if($jadwal['status'] === 'Full') bg-danger
                                                        @elseif($jadwal['status'] === 'Close') bg-secondary
                                                        @elseif($jadwal['status'] === 'Tersedia') bg-success
                                                        @else bg-light text-muted
                                                        @endif">
                                                        {{ $jadwal['status'] }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    <!-- === PASIEN === -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-primary"><i class="bi bi-people-fill"></i> Daftar Pasien</h6>
                        <a href="{{ route('dokter.jadwal_harian') }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-clipboard-pulse"></i> Info Pasien Detail
                        </a>
                    </div>

                    @php $pasienHariIni = $item->pasien_hari_ini ?? collect(); @endphp

                    @if ($pasienHariIni->isEmpty())
                        <div class="alert alert-secondary"><i class="bi bi-info-circle"></i> Belum ada pasien yang berkunjung ke layanan ini hari ini.</div>
                    @else
                        <div class="table-responsive" style="overflow-x: auto;">
                            <table class="table table-bordered table-sm datatable-dokter" style="width: 100%;">
                                <thead class="table-light">
                                    <tr>
                                        <th>No Antrian</th>
                                        <th>Ruangan</th>
                                        <th>Nama Pasien</th>
                                        <th>Keluhan</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pasienHariIni as $kunjungan)
                                        <tr>
                                            <td>{{ $kunjungan->Nomor_Urut ?? '-' }}</td>
                                            <td>{{ $kunjungan->ruangan->Nama_Ruangan ?? '-' }}</td>
                                            <td>{{ $kunjungan->pasien->Nama_Pasien ?? $kunjungan->Nama_Pasien ?? '-' }}</td>
                                            <td>{{ $kunjungan->Keluhan ?? '-' }}</td>
                                            <td>
                                                <span class="badge-status 
                                                    @if ($kunjungan->Status === 'Selesai') bg-success
                                                    @elseif ($kunjungan->Status === 'Menunggu') bg-warning text-dark
                                                    @elseif ($kunjungan->Status === 'Diperiksa') bg-info text-dark
                                                    @else bg-secondary
                                                    @endif">
                                                    {{ ucfirst($kunjungan->Status ?? '-') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
                <div class="modal-footer d-flex justify-content-between align-items-center">
                    <!-- Kiri -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('dokter.jadwal_harian') }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-calendar2-week"></i> Info Jadwal Lengkap
                        </a>
                        <a href="{{ route('dokter.jadwal_harian') }}" class="btn btn-sm btn-outline-info">
                            <i class="bi bi-clipboard-pulse"></i> Info Pasien Detail
                        </a>
                    </div>

                    <!-- Kanan -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endforeach


<script type="application/json" id="notifikasi-data">
    {!! $indikasi_belum_dibaca->map(function($item) {
        return [
            "kode" => $item->Id_Layanan,
            "Nama_Layanan" => $item->Nama_Layanan,
            "waktu" => \Carbon\Carbon::parse($item->Create_Date)->locale("id")->translatedFormat("d M Y H:i")
        ];
    })->toJson() !!}
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notifbtn = document.querySelector('.notification-btn');
        const modalnotif = document.getElementById('notifikasi_list');
        const modal = new bootstrap.Modal(document.getElementById('modal_new_notifikasi'));

        if (!notifbtn || !modalnotif) return;

        const jumlah_notifikasi = JSON.parse(document.getElementById('notifikasi-data').textContent);

        notifbtn.addEventListener('click', function() {
            if (jumlah_notifikasi.length === 0) {
                modalnotif.innerHTML = `<p class="text-muted">Tidak ada notifikasi baru.</p>`;
            } else {
                let html = '<ul class="list-group">';
                jumlah_notifikasi.forEach(item => {
                    html += `
                        <div class="notification-container container mt-3">
                            <ul class="notification-list list-group">
                                <li class="notification-item list-group-item d-flex flex-column gap-2">
                                    <!-- Header (Kode) -->
                                    <div class="notification-header fw-bold text-primary">
                                        <i class="bi bi-tag-fill me-1"></i>[${item.kode}]
                                    </div>
                                    <!-- Konten (Nama Layanan penyakit) -->
                                    <div class="notification-content">
                                        <i class="bi bi-heart-pulse text-danger me-1"></i>
                                        Indikasi penyakit: <b>${item.Nama_Layanan}</b>
                                    </div>
                                    <!-- Footer (Waktu) -->
                                    <div class="notification-footer">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            Dibuat pada <b>${item.waktu}</b>
                                        </small>
                                    </div>
                                </li>
                            </ul>
                        </div>`;
                });
                html += '</ul>';
                modalnotif.innerHTML = html;
            }

            modal.show();

            // Hapus badge
            let badge = document.querySelector('.notification-badge');
            if (badge) badge.remove();

            // Tandai sebagai dibaca
            fetch("{{ route('indikasi.clear_notification') }}");
        });
    });

    // Modal Total Indikasi
    document.addEventListener('DOMContentLoaded', function () {
        const modalIndikasi = new bootstrap.Modal(document.getElementById('total_indikasi'));
    });
</script>
@endsection
