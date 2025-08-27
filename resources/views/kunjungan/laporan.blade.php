@extends('layouts.nav_admin')
@section('title', 'Laporan Kunjungan')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-primary fw-bold mb-1">
                        <i class="fas fa-chart-line me-2"></i>Laporan Kunjungan Pasien
                    </h3>
                    <p class="text-muted mb-0">Kelola dan pantau data kunjungan pasien</p>
                </div>
                <a href="{{ route('kunjungan.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Export Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm w-auto">
                <div class="card-body py-3">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('kunjungan.export.pdf', request()->query()) }}" 
                           class="btn btn-danger btn-sm">
                            <i class="fas fa-file-pdf me-2"></i>Export PDF
                        </a>
                        <a href="{{ route('kunjungan.export.excel', request()->query()) }}" 
                           class="btn btn-success btn-sm">
                            <i class="fas fa-file-excel me-2"></i>Export Excel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h6 class="mb-0 text-primary">
                        <i class="fas fa-filter me-2"></i>Filter Data
                    </h6>
                </div>
                <div class="card-body">
                    <form method="GET">
                        <div class="row g-3">
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label fw-semibold">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="form-control" 
                                       value="{{ request('start_date') }}">
                            </div>
                            
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label fw-semibold">Tanggal Sampai</label>
                                <input type="date" name="end_date" class="form-control" 
                                       value="{{ request('end_date') }}">
                            </div>
                            
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label fw-semibold">Dokter</label>
                                <select name="dokter_id" class="form-select">
                                    <option value="">Semua Dokter</option>
                                    @foreach($dokters as $dokter)
                                        <option value="{{ $dokter->Id_Dokter }}" 
                                                {{ request('dokter_id') == $dokter->Id_Dokter ? 'selected' : '' }}>
                                            {{ $dokter->Nama_Dokter }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 col-lg-3">
                                <label class="form-label fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>
                                        Selesai
                                    </option>
                                    <option value="Tidak Selesai" {{ request('status') == 'Tidak Selesai' ? 'selected' : '' }}>
                                        Tidak Selesai
                                    </option>
                                    <option value="Diperiksa" {{ request('status') == 'Diperiksa' ? 'selected' : '' }}>
                                        Diperiksa
                                    </option>
                                    <option value="Menunggu" {{ request('status') == 'Menunggu' ? 'selected' : '' }}>
                                        Menunggu
                                    </option>
                                </select>
                            </div>
                            
                            <div class="col-md-6 col-lg-3 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="fas fa-search me-2"></i>Filter
                                </button>
                                <a href="{{ route('kunjungan.laporan') }}" class="btn btn-outline-secondary flex-fill">
                                    <i class="fas fa-undo me-2"></i>Reset
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Data Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white border-0">
                    <h6 class="mb-0">
                        <i class="fas fa-table me-2"></i>Data Kunjungan
                    </h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-3 py-3 text-center">No</th>
                                    <th class="px-3 py-3">Nama Pasien</th>
                                    <th class="px-3 py-3">NIK</th>
                                    <th class="px-3 py-3">No Telepon</th>
                                    <th class="px-3 py-3">Keluhan</th>
                                    <th class="px-3 py-3 text-center">No. Urut</th>
                                    <th class="px-3 py-3">Dokter</th>
                                    <th class="px-3 py-3">Layanan</th>
                                    <th class="px-3 py-3">Jam</th>
                                    <th class="px-3 py-3">Ruangan</th>
                                    <th class="px-3 py-3">Tgl Registrasi</th>
                                    <th class="px-3 py-3">Jadwal Kedatangan</th>
                                    <th class="px-3 py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kunjungan as $i => $item)
                                <tr>
                                    <td class="px-3 py-3 text-center">
                                        <span class="badge bg-light text-dark">{{ $i + 1 }}</span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <div class="fw-semibold">{{ $item->Nama_Pasien }}</div>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="text-muted">{{ $item->Nik }}</span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="text-muted">{{ $item->pasien->No_Tlp ??'-'}}</span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="text-muted">{{ $item->Keluhan}}</span>
                                    </td>
                                    <td class="px-3 py-3 text-center">
                                        <span class="badge bg-info text-white">{{ $item->Nomor_Urut }}</span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="text-primary fw-semibold">
                                            {{ $item->dokter->Nama_Dokter ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="text-muted">{{ $item->Id_Layanan->Nama_Layanan ?? '-' }}</span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="text-muted">{{ $item->Jadwal}}</span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="text-muted">{{ $item->ruangan->Nama_Ruangan ??'-'}}</span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="text-muted">
                                            {{ \Carbon\Carbon::parse($item->Tanggal_Registrasi)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3">
                                        <span class="text-muted">
                                            {{ \Carbon\Carbon::parse($item->Jadwal_Kedatangan)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-3">
                                        @php
                                            $statusClass = match($item->Status) {
                                                'Selesai' => 'success',
                                                'diperiksa' => 'info',
                                                'menunggu' => 'primary',
                                                'Belum Hadir' => 'danger',
                                                default => 'secondary'
                                            };
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $item->Status }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-inbox fa-3x mb-3"></i>
                                            <p class="mb-0">Tidak ada data kunjungan ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 8px;
    }

    .table th {
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        font-size: 0.875rem;
    }

    .table td {
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        box-shadow: inset 0 0 4px rgba(0,0,0,0.05);
    }

    .btn {
        border-radius: 6px;
        font-weight: 500;
    }

    .form-control, .form-select {
        border-radius: 6px;
        border: 1px solid #dee2e6;
        box-shadow: 0 2px 3px rgba(0,0,0,0.08);
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 500;
    }

    .shadow-sm {
        box-shadow: 0 0.200rem 0.50rem rgba(0, 0, 0, 0.075) !important;
    }
    </style>
@endsection