@extends('layouts.nav_admin')
@section('title', 'Dokter')
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
                    {{ __('messages.Daftar_Dokter') }}
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

<div class="container-fluid mt-4" id="dokter-table-container">  
    <!-- Tabel Data -->
    <form method="POST" action="{{ route('dokter.select_delete') }}" id="select-delete-form">
        @csrf
        @method('DELETE') 

        <!-- Header & Tombol Aksi -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <span id="liveTime" class="badge bg-info-subtle text-dark fs-6">--:--</span>
            <div class="mt-2 mt-md-0">
                <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#modalTambahDokter">
                    <i class="bi bi-plus-circle me-1"></i> {{ __('messages.Tambah_Dokter') }}
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
                    {{ __('messages.Daftar_Dokter') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table" class="table table-bordered table-hover table-striped table-responsive nowrap align-middle text-center">
                        <thead class="table-primary">
                            <tr class="text-start align-middle">
                                <th style="width: 40px;">
                                    <input type="checkbox" id="select-all" data-type="sesi">
                                </th>
                                <th style="width: 60px;">{{ __('messages.Opsi') }}</th>
                                <th style="width: 40px;">{{ __('messages.No') }}</th>
                                <th style="min-width: 180px;">{{ __('messages.Nama_Dokter') }}</th>
                                <th style="min-width: 150px;">{{ __('messages.Spesialis') }}</th>
                                <th style="min-width: 130px;" class="text-nowrap">{{ __('messages.No_Telephone') }}</th>
                                <th style="min-width: 180px;" class="text-nowrap">{{ __('messages.Email') }}</th>
                                <th style="min-width: 300px;">{{ __('messages.Jadwal_Dokter') }}</th>
                                <th style="min-width: 150px;" class="text-nowrap">{{ __('messages.Tanggal_Dibuat') }}</th>
                            </tr>

                        </thead>

                        <tbody>
                            @forelse ($data as $item)
                                <tr class="text-start">
                                    {{-- Checkbox --}}
                                    <td>
                                        <input type="checkbox" name="selected_dokter[]" value="{{ $item->Id_Dokter }}">
                                    </td>

                                    {{-- Aksi --}}
                                    <td>
                                        <a href="{{ route('dokter.edit', $item->Id_Dokter) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                    </td>

                                    {{-- Nomor urut --}}
                                    <td>{{ $loop->iteration }}</td>

                                    {{-- Nama Dokter --}}
                                    <td class="fw-semibold">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalNamaDokter{{ $item->Id_Dokter }}">
                                            @if (Lang::has('messages.' . $item->Nama_Dokter))
                                                {{ __('messages.' . $item->Nama_Dokter) }}
                                            @else
                                                <span data-translate="{{ $item->Nama_Sesi }}">{{ $item->Nama_Dokter }}</span>
                                            @endif
                                        </a>
                                    </td>

                                    {{-- Spesialis, Telp, Email --}}
                                    <td class="text-center">{{ $item->Spesialis }}</td>
                                    <td class="text-center">{{ $item->No_Telp }}</td>
                                    <td class="text-center">{{ $item->Email }}</td>

                                    {{-- Jadwal Dokter --}}
                                    <td class="text-start">
                                    @php
                                        $jadwal = json_decode($item->Jadwal_Dokter, true) ?? [];
                                    @endphp

                                    @foreach ($jadwal as $hari => $list)
                                        @php
                                            $filteredList = collect($list)->filter(function ($j) use ($namaRuangan) {
                                                return !$namaRuangan || strtolower($j['ruang'] ?? '') === strtolower($namaRuangan);
                                            });
                                        @endphp

                                        @if ($filteredList->isNotEmpty())
                                        <div class="mb-2">
                                            <div class="fw-bold text-primary">{{ ucfirst($hari) }}</div>

                                            <table class="table table-sm table-bordered mb-0">
                                                <thead class="table-light">
                                                    <tr class="text-center small">
                                                        <th>Jam</th>
                                                        <th>Kuota</th>
                                                        <th>terpakai</th>
                                                        <th>Sisa</th>
                                                        <th>Ruang</th>
                                                        <th>Sesi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($list as $entry)
                                                        @php
                                                            $kuota = $entry['kuota'] ?? 0;
                                                            $start = $entry['start'] ?? '-';
                                                            $end = $entry['end'] ?? '-';
                                                            $range = ($start && $end) ? "$start - $end" : null;

                                                            $terpakai = \App\Models\Kunjungan::where('Id_Dokter', $item->Id_Dokter)
                                                                ->where('Jadwal', $range)
                                                                ->whereDate('Jadwal_Kedatangan', '>=', now()->toDateString())
                                                                ->count();

                                                            $sisa = $kuota - $terpakai;
                                                        @endphp

                                                        <tr class="text-center small">
                                                            <td>{{ $start }} - {{ $end }}</td>
                                                            <td>{{ $kuota }}</td>
                                                            <td>{{ $terpakai }}</td>
                                                            <td>{{ $sisa }}</td>
                                                            <td>{{ $entry['ruang'] ?? '-' }}</td>
                                                            <td>{{ $entry['sesi'] ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                        @endif
                                    @endforeach
                                    </td>

                                    {{-- Tanggal Dibuat --}}
                                    <td class="text-center">{{ $item->Create_Date }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4">
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



<div class="container-fluid mt-4">  
    <div class="card cardstd shadow-sm p-3 mb-4 mh-50">
        <div class="row align-items-center justify-content-between g-2 mb-3">
            <form method="GET" action="{{ route('dokter.index') }}" class="mb-3" id="filter-form">
                <div class="col-md-auto d-flex align-items-center gap-3">
                    <select name="limit" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="" disabled selected hidden>Pilih Baris Data Yang Akan Di Tampilkan</option>
                        <option value="5" {{ request('limit') == 5 ? 'selected' : '' }}>5</option>
                        <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                    </select>

                    <div class="col-md-5">
                        <input type="text" name="date_range" id="date_range" class="form-control form-control-sm"
                            value="{{ request('date_range') }}" placeholder="Pilih rentang tanggal"/>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <form method="POST" action="{{ route('dokter.select_delete') }}" id="select-delete-form">
        @csrf
        @method('DELETE')

        <div class="row align-items-center mb-3">
            <div class="col">
                <span id="liveTime" class="live_waktu badge fs-6"></span>
            </div>
            <div class="col-md-auto text-md-end">
                <a href="{{ route('dokter.create') }}" class="btn btn-primary btn-sm me-2">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Dokter
                </a>
                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin menghapusnya?')">
                    🗑️ Hapus Data
                </button>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0 custom-table">
                    <thead class="table-primary text-center">
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th style="width: 20px;">Tombol</th>
                            <th style="width: 50px;">No</th>
                            <th style="width: 100px;">Nama Dokter</th>
                            <th style="width: 100px">Spesialis</th>
                            <th style="width: 100px">No Telephone</th>
                            <th style="width: 180px;">Email</th>
                            <th style="width: 300px;">Jadwal Dokter</th>
                            <th style="width: 180px;">Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $item)
                            <tr>
                                <td class="text-center">
                                    <input type="checkbox" name="selected_dokter[]" value="{{ $item->Id_Dokter }}">
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('dokter.edit', $item->Id_Dokter) }}" class="edit btn btn-sm btn-outline-warning me-1 mb-1">Ubah</a>
                                    <form action="{{ route('dokter.destroy', $item->Id_Dokter) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="hapus btn btn-sm btn-outline-danger mb-1">Hapus</button>
                                    </form>
                                </td>
                                <td class="text-center">{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                <td class="text-center">{{ $item->Nama_Dokter }}</td>
                                <td class="text-center">{{ $item->Spesialis }}</td>
                                <td class="text-center">{{ $item->No_Telp }}</td>
                                <td class="text-center">{{ $item->Email }}</td>
                                <td>
                                    @php
                                        $jadwal = json_decode($item->Jadwal_Dokter, true) ?? [];
                                    @endphp

                                    <ul class="mb-0 ps-3">
                                        @foreach ($jadwal as $hari => $list)
                                            <li><strong>{{ $hari }}:</strong>
                                                <ul class="mb-1 ps-3">
                                                @foreach ($list as $entry)
                                                    <li>
                                                        {{ $entry['start'] ?? '-' }} - {{ $entry['end'] ?? '-' }}
                                                        @php
                                                            $kuota = $entry['kuota'] ?? 0;
                                                            $start = $entry['start'] ?? null;

                                                            $terpakai = \App\Models\Kunjungan::where('Id_Dokter', $item->Id_Dokter)
                                                                ->whereTime('Jadwal', $start)
                                                                ->whereDate('Tanggal_Registrasi', now()->toDateString())
                                                                ->count();

                                                            $sisa = max(0, $kuota - $terpakai);
                                                        @endphp

                                                        (Kuota: {{ $kuota }} | Terpakai: {{ $terpakai }} | Sisa: {{ $sisa }})
                                                        @if(isset($entry['ruang']))
                                                            – Ruangan: {{ $entry['ruang'] }}
                                                        @endif
                                                        @if(isset($entry['sesi']))
                                                            – <span class="text-muted">Sesi: {{ $entry['sesi'] }}</span>
                                                        @endif
                                                    </li>
                                                @endforeach

                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="text-center">{{ $item->Create_Date }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">Tidak ada data sesi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <span class="anti_salin stats-text text-muted mb-2 mb-md-0">
                <strong>Total Sesi:</strong> {{ $data->total() }} |
                <strong>Ditampilkan:</strong> {{ $data->count() }} |
                <strong>Halaman:</strong> {{ $data->currentPage() }} / {{ $data->lastPage() }} |
                <strong>Baris:</strong> {{ $data->firstItem() }} - {{ $data->lastItem() }}
            </span>

            <div class="mb-0 mt-3">
                {{ $data->withQueryString()->onEachSide(1)->links() }}
            </div>
        </div>
    </form>
</div>

<script>
function addJadwal(hari) {
    const container = document.getElementById('jadwal-' + hari);
    const row = document.createElement('div');
    row.className = 'row mb-2';

    row.innerHTML = 
        <div class="col-md-4">
            <input type="time" name="jadwal[${hari}][start][]" class="form-control" placeholder="Jam Mulai">
        </div>
        <div class="col-md-4">
            <input type="time" name="jadwal[${hari}][end][]" class="form-control" placeholder="Jam Selesai">
        </div>
        <div class="col-md-3">
            <input type="number" name="jadwal[${hari}][kuota][]" class="form-control" placeholder="Kuota" min="1">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-sm btn-danger" onclick="this.parentElement.parentElement.remove()">-</button>
        </div>
    ;

    container.appendChild(row);
}
</script>

@endsection