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

<div class="main-content">
    <div class="welcome-section fade-in">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="mb-2">Selamat Datang, Dr. {{ Auth::user()->username }}</h2>
                <p class="mb-0 opacity-75">Hari ini Anda memiliki 12 pasien terjadwal dan 3 konsultasi darurat</p>
            </div>
            <div class="col-md-4 text-end">
                <div class="fs-1">
                    <i class="bi bi-calendar-date"></i>
                </div>
                <div class="mt-2">
                    <small id="currentDateTime"></small>
                </div>
            </div>
        </div>
    </div>

    <p class="text-muted mb-2">Total Pasien</p>
    <small class="text-success">
        <i class="bi bi-arrow-up"></i> +12% dari bulan lalu
    </small>
</div>
</div>
<div class="col-lg-3 col-md-6 mb-3">
    <div class="stats-card fade-in" style="animation-delay: 0.1s;">
        <div class="stats-icon appointments">
            <i class="bi bi-calendar-check"></i>
        </div>
        <h3 class="fw-bold mb-1">324</h3>
        <p class="text-muted mb-2">Janji Temu</p>
        <small class="text-warning">
            <i class="bi bi-clock"></i> 12 hari ini
        </small>
    </div>
</div>
<div class="col-lg-3 col-md-6 mb-3">
    <div class="stats-card fade-in" style="animation-delay: 0.2s;">
        <div class="stats-icon revenue">
            <i class="bi bi-currency-dollar"></i>
        </div>
        <h3 class="fw-bold mb-1">Rp 45.8M</h3>
        <p class="text-muted mb-2">Pendapatan</p>
        <small class="text-success">
            <i class="bi bi-arrow-up"></i> +8% dari bulan lalu
        </small>
    </div>
</div>
<div class="col-lg-3 col-md-6 mb-3">
    <div class="stats-card fade-in" style="animation-delay: 0.3s;">
        <div class="stats-icon prescriptions">
            <i class="bi bi-prescription2"></i>
        </div>
        <h3 class="fw-bold mb-1">186</h3>
        <p class="text-muted mb-2">Resep Dibuat</p>
        <small class="text-info">
            <i class="bi bi-info-circle"></i> Minggu ini
        </small>
    </div>
</div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="mb-3">Aksi Cepat</h5>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <a href="#" class="quick-action-card d-block slide-in">
            <div class="action-icon">
                <i class="bi bi-person-plus"></i>
            </div>
            <h6 class="fw-bold mb-2">Daftar Pasien Baru</h6>
            <p class="text-muted small mb-0">Tambah pasien baru ke sistem</p>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <a href="#" class="quick-action-card d-block slide-in" style="animation-delay: 0.1s;">
            <div class="action-icon">
                <i class="bi bi-calendar-plus"></i>
            </div>
            <h6 class="fw-bold mb-2">Buat Jadwal</h6>
            <p class="text-muted small mb-0">Atur jadwal konsultasi baru</p>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <a href="#" class="quick-action-card d-block slide-in" style="animation-delay: 0.2s;">
            <div class="action-icon">
                <i class="bi bi-file-earmark-medical"></i>
            </div>
            <h6 class="fw-bold mb-2">Tulis Resep</h6>
            <p class="text-muted small mb-0">Buat resep untuk pasien</p>
        </a>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <a href="#" class="quick-action-card d-block slide-in" style="animation-delay: 0.3s;">
            <div class="action-icon">
                <i class="bi bi-hospital"></i>
            </div>
            <h6 class="fw-bold mb-2">Konsultasi Darurat</h6>
            <p class="text-muted small mb-0">Tangani kasus darurat</p>
        </a>
    </div>
</div>

<!-- Content Row -->
<div class="row">
    <!-- Recent Activity -->
    <div class="col-lg-4 mb-4">
        <div class="activity-card fade-in">
            <div class="card-header">
                <h6 class="fw-bold mb-0">Aktivitas Terbaru</h6>
            </div>
            <div class="activity-item d-flex align-items-center">
                <div class="activity-icon appointment me-3">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">Konsultasi dengan Sarah Ahmad</h6>
                    <small class="text-muted">2 menit yang lalu</small>
                </div>
            </div>
            <div class="activity-item d-flex align-items-center">
                <div class="activity-icon prescription me-3">
                    <i class="bi bi-prescription2"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">Resep untuk Muhammad Rizki</h6>
                    <small class="text-muted">15 menit yang lalu</small>
                </div>
            </div>
            <div class="activity-item d-flex align-items-center">
                <div class="activity-icon emergency me-3">
                    <i class="bi bi-exclamation-triangle"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">Kasus darurat - Siti Nurhaliza</h6>
                    <small class="text-muted">1 jam yang lalu</small>
                </div>
            </div>
            <div class="activity-item d-flex align-items-center">
                <div class="activity-icon appointment me-3">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1">Jadwal dengan Budi Santoso</h6>
                    <small class="text-muted">2 jam yang lalu</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Appointments -->
    <div class="col-lg-8 mb-4">
        <div class="appointments-card fade-in">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0">Jadwal Hari Ini</h6>
                <button class="btn btn-sm btn-outline-primary">Lihat Semua</button>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Pasien</th>
                            <th>Jenis Layanan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>09:00</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://images.unsplash.com/photo-1494790108755-2616b332174c?w=40&h=40&fit=crop&crop=face" class="rounded-circle me-2" width="32" height="32">
                                    <div>
                                        <div class="fw-medium">Aisha Rahman</div>
                                        <small class="text-muted">ID: P001</small>
                                    </div>
                                </div>
                            </td>
                            <td>Konsultasi Umum</td>
                            <td><span class="status-badge completed">Selesai</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary">Detail</button>
                            </td>
                        </tr>
                        <tr>
                            <td>10:30</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=40&h=40&fit=crop&crop=face" class="rounded-circle me-2" width="32" height="32">
                                    <div>
                                        <div class="fw-medium">Rizki Pratama</div>
                                        <small class="text-muted">ID: P002</small>
                                    </div>
                                </div>
                            </td>
                            <td>Pemeriksaan Rutin</td>
                            <td><span class="status-badge scheduled">Terjadwal</span></td>
                            <td>
                                <button class="btn btn-sm btn-primary">Mulai</button>
                            </td>
                        </tr>
                        <tr>
                            <td>11:45</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=40&h=40&fit=crop&crop=face" class="rounded-circle me-2" width="32" height="32">
                                    <div>
                                        <div class="fw-medium">Sari Indira</div>
                                        <small class="text-muted">ID: P003</small>
                                    </div>
                                </div>
                            </td>
                            <td>Konsultasi Khusus</td>
                            <td><span class="status-badge cancelled">Dibatalkan</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-secondary">Reschedule</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

@endsection