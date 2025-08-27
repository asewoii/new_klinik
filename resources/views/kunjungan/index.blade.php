@extends('layouts.nav_admin')

@section('title', 'Kunjungan')

@section('content')
<div class="container-fluid mt-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="text-primary fw-bold mb-1">
                        <i class="fas fa-users me-2"></i>Daftar Kunjungan Pasien
                    </h3>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('kunjungan.laporan') }}" class="btn btn-success">
                        <i class="fas fa-file-alt me-2"></i>Cetak Laporan
                    </a>
                    <a href="{{ url('/admin') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Alert -->
    @if (session('success'))
    <div class="row mb-3">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Search & Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('kunjungan.index') }}">
                        <div class="row g-3 align-items-end">
                            <!-- Search Input -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Pencarian</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0" 
                                           placeholder="Cari nama pasien atau NIK..." 
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <!-- Limit Selection -->
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Tampilkan</label>
                                <select name="limit" class="form-select" onchange="this.form.submit()">
                                    <option value="5" {{ request('limit') == 5 ? 'selected' : '' }}>5 baris</option>
                                    <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10 baris</option>
                                    <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25 baris</option>
                                    <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50 baris</option>
                                </select>
                            </div>

                            <!-- Search Button -->
                            <div class="col-md-3">
                                <button class="btn btn-primary w-100" type="submit">
                                    <i class="fas fa-search me-2"></i>Cari Data
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body py-3">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-chart-bar text-primary me-2"></i>
                                <span class="text-muted">Total Kunjungan: </span>
                                <span class="fw-bold text-primary ms-1">{{ $kunjungan->total() }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-eye text-success me-2"></i>
                                <span class="text-muted">Ditampilkan: </span>
                                <span class="fw-bold text-success ms-1">{{ $kunjungan->count() }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center justify-content-center">
                                <i class="fas fa-file-alt text-info me-2"></i>
                                <span class="text-muted">Halaman: </span>
                                <span class="fw-bold text-info ms-1">{{ $kunjungan->currentPage() }} dari {{ $kunjungan->lastPage() }}</span>
                            </div>
                        </div>
                    </div>
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
                        <i class="fas fa-table me-2"></i>Data Kunjungan Pasien
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if ($kunjungan->isEmpty())
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Tidak ada data kunjungan</h5>
                            <p class="text-muted">Belum ada data kunjungan yang tersedia atau sesuai dengan pencarian Anda.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="px-3 py-3 text-center" style="width: 60px;">#</th>
                                        <th class="px-3 py-3 text-center" style="width: 120px;">QR Code</th>
                                        <th class="px-3 py-3">Nama Pasien</th>
                                        <th class="px-3 py-3">NIK</th>
                                        <th class="px-3 py-3">No Telepon</th>
                                        <th class="px-3 py-3">Keluhan</th>
                                        <th class="px-3 py-3 text-center">No. Urut</th>
                                        <th class="px-3 py-3">Dokter</th>
                                        <th class="px-3 py-3">Layanan</th>
                                        <th class="px-3 py-3">Ruangan</th>
                                        <th class="px-3 py-3">Jadwal</th>
                                        <th class="px-3 py-3">Status</th>
                                        <th class="px-3 py-3">Jadwal Kedatangan</th>
                                        <th class="px-3 py-3">Tgl Registrasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kunjungan as $i => $item)
                                    <tr>
                                        <td class="px-3 py-3 text-center">
                                            <span class="badge bg-light text-dark">{{ $i + $kunjungan->firstItem() }}</span>
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            <div class="qr-code-container">
                                                {!! QrCode::size(80)->generate($item->QRK) !!}
                                            </div>
                                        </td>
                                        <td class="px-3 py-3">
                                            <div class="fw-semibold text-primary">{{ $item->Nama_Pasien }}</div>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-muted">{{ $item->Nik }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-muted">{{ $item->pasien->No_Tlp ?? '-' }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-muted">{{ $item->Keluhan  }}</span>
                                        </td>
                                        <td class="px-3 py-3 text-center">
                                            <span class="badge bg-info text-white">{{ $item->Nomor_Urut }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-muted">{{ $item->dokter->Nama_Dokter ?? '-' }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-muted">{{ $item->layanan->Nama_Layanan ?? '-' }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-muted">{{ $item->ruangan->Nama_Ruangan ?? '-' }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-muted">{{ $item->Jadwal  }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            @php
                                                $statusClass = match($item->Status) {
                                                    'Selesai' => 'success',
                                                    'Diperiksa' => 'info',
                                                    'menunggu' => 'primary',
                                                    'Belum Hadir' => 'danger',
                                                    default => 'secondary'
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">{{ $item->Status }}</span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-muted">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ \Carbon\Carbon::parse($item->Jadwal_Kedatangan)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                        <td class="px-3 py-3">
                                            <span class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ \Carbon\Carbon::parse($item->Tanggal_Registrasi)->format('d/m/Y') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if ($kunjungan->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $kunjungan->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
    @endif
</div>

    
<style>
    /* Custom Styles */
    .card {
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .table {
        font-size: 0.9rem;
    }

    .table th {
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        font-size: 0.875rem;
        color: #495057;
    }

    .table td {
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
        transform: scale(1.01);
        transition: all 0.2s ease;
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        transition: all 0.3s ease;
    }

    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
        transform: translateY(-1px);
    }

    .badge {
        font-size: 0.75rem;
        font-weight: 500;
        padding: 0.5em 0.75em;
    }

    .shadow-sm {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }

    .qr-code-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0.5rem;
    }

    .qr-code-container svg {
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 4px;
        background: white;
    }

    /* Alert Styles */
    .alert {
        border-radius: 10px;
        border: none;
    }

    /* Pagination Styles */
    .pagination {
        font-size: 0.875rem;
    }

    .page-link {
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        margin: 0 2px;
        border: 1px solid #dee2e6;
        color: #495057;
        transition: all 0.3s ease;
    }

    .page-link:hover {
        background-color: #e9ecef;
        transform: translateY(-1px);
    }

    .page-item.active .page-link {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 1rem;
        }
        
        .table {
            font-size: 0.8rem;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .btn-group .btn {
            margin-bottom: 0.25rem;
        }
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card {
        animation: fadeIn 0.5s ease-out;
    }
</style>
@endsection