@extends('layouts.nav_admin')
@section('title', 'Pasien')
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
                    {{ __('messages.Pasien') }}
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

<div class="container-fluid mt-4" id="pasien-table-container">
    <!-- Tabel Data -->
    <form method="POST" action="{{ route('pasien.select_delete') }}" id="select-delete-form">
        @csrf
        @method('DELETE')

        <!-- Header & Tombol Aksi -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <span id="liveTime" class="badge bg-info-subtle text-dark fs-6">--:--</span>
            <div class="mt-2 mt-md-0">
                <a href="{{ route('pasien.create') }}" class="btn btn-primary btn-sm me-2">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Pasien
                </a>

                <button type="submit" class="btn btn-sm btn-danger btn-trigger-delete" data-form="#select-delete-form" data-type="pasien">
                    <i class="bi bi-trash me-1"></i> {{ __('messages.Hapus_Data') }}
                </button>
            </div>
        </div>

        <!-- Tabel -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    {{ __('messages.Daftar_Pasien') }}
                </h5>
            </div>

            <div class="card-body">
                <div class="table-responsive" style="overflow-x: auto;">
                    <table id="table" class="custom-table table table-bordered table-hover table-striped align-middle text-center" style="width: 100%;">
                        <thead class="table-primary">
                            <tr class="text-start">
                                <th><input type="checkbox" id="select-all" data-type="pasien"></th>
                                <th>{{ __('messages.Opsi') }}</th>
                                <th>{{ __('messages.No') }}</th>
                                <th>Qr Pasien</th>
                                <th>Nama Pasien</th>
                                <th>NIK Pasien</th>
                                <th>Tanggal Lahir</th>
                                <th>Umur</th>
                                <th>Jenis Kelamin</th>
                                <th>Alamat</th>
                                <th>No. Telepon</th>
                                <th>Tgl Pendaftaran</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($data as $item)
                                <tr class="text-start">
                                    <td>
                                        <input type="checkbox" name="selected_pasien[]" value="{{ $item->Id_Pasien }}">
                                    </td>

                                    <td>
                                        <a href="{{ route('pasien.edit', $item->Id_Pasien) }}" class="btn btn-sm btn-warning me-2 btn-edit-pasien me-1 mb-1">
                                            <i class="bi bi-pencil-square me-1"></i>
                                        </a>

                                        <a href="{{ route('admin.kunjungan.create', ['id' => $item->Id_Pasien]) }}" class="btn btn-sm btn-outline-primary mb-1">
                                            Form Kunjungan
                                        </a>
                                        
                                        <button type="button" class="btn btn-sm btn-outline-info mb-1 btn-history" 
                                            data-id="{{ $item->Id_Pasien }}" data-nama="{{ $item->Nama_Pasien }}">
                                            <i class="bi bi-clock-history me-1"></i> Riwayat
                                        </button>
                                    </td>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        <div class="qr-code-box">
                                            {!! QrCode::size(80)->generate($item->Qr_Url) !!}
                                        </div>
                                    </td>

                                    <td>{{ Str::limit($item->Nama_Pasien, 20) }}</td>
                                    <td>{{ $item->Nik }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->Tanggal_Lahir)->locale('id')->translatedFormat('l, d F Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->Tanggal_Lahir)->age }} tahun</td>
                                    <td>{{ strtoupper($item->Jk) }}</td>
                                    <td>{{ Str::limit($item->Alamat, 40) }}</td>
                                    <td>{{ $item->No_Tlp }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->Tanggal_Registrasi)->locale('id')->translatedFormat('l, d F Y ') }}</td>
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










<!-- Modal Notifikasi -->
<div class="modal fade" id="modal_new_notifikasi" tabindex="-1" aria-labelledby="modalNotifikasiLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNotifikasiLabel">📢 Notifikasi Pasien Baru</h5>
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

<!-- Modal Riwayat Kunjungan -->
<div class="modal fade" id="modalHistory" tabindex="-1" aria-labelledby="modalHistoryLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalHistoryLabel">Riwayat Kunjungan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body" id="historyContent">
        <p class="text-muted">Memuat data...</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>


<script type="application/json" id="notifikasi-data">
    {!! $pasien_belum_dibaca->map(function($item) {
        return [
            "nama" => $item->Nama_Pasien,
            "umur" => \Carbon\Carbon::parse($item->Tanggal_Lahir)->age,
            "alamat" => Str::limit($item->Alamat, 40),
            "jenis_kelamin" => $item->Jk,
            "no_tlp" => $item->No_Tlp,
            "tgl_pendaftaran" => \Carbon\Carbon::parse($item->Tanggal_Registrasi)->locale('id')->translatedFormat('l, d F Y H:i:s')
        ];
    })->toJson() !!}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const historyModal = new bootstrap.Modal(document.getElementById('modalHistory'));
    const historyContent = document.getElementById('historyContent');
    const historyTitle = document.getElementById('modalHistoryLabel');

    document.querySelectorAll('.btn-history').forEach(btn => {
        btn.addEventListener('click', function() {
            const pasienId = this.dataset.id;
            const pasienNama = this.dataset.nama;
            
            historyTitle.textContent = `Riwayat Kunjungan - ${pasienNama}`;
            historyContent.innerHTML = '<p class="text-muted">Memuat data...</p>';
            historyModal.show();

            fetch(`/admin/kunjungan/history/${pasienId}`)
                .then(res => res.text())
                .then(html => {
                    historyContent.innerHTML = html;
                })
                .catch(err => {
                    historyContent.innerHTML = `<div class="alert alert-danger">Gagal memuat data.</div>`;
                    console.error(err);
                });
        });
    });
});
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
                        <li class="list-group-item">
                            <strong>[${item.nama}, ${item.umur} Tahun]</strong><br>
                            📍 Alamat: ${item.alamat}<br>
                            📞 No. Telepon: ${item.no_tlp}<br>
                            🕒 Terdaftar pada: ${item.tgl_pendaftaran}
                        </li>`;
                });
                html += '</ul>';
                modalnotif.innerHTML = html;
            }

            modal.show();

            // Hapus badge
            let badge = document.querySelector('.notification-badge');
            if (badge) badge.remove();

            // Tandai sebagai dibaca
            fetch("{{ route('pasien.clear_notification') }}");
        });
    });
</script>
@endsection
