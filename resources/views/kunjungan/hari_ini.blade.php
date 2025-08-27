@extends('layouts.nav_admin')
@section('title', 'Kunjungan Hari Ini')

@section('content')
<div class="container">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center px-3 py-3">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="bi bi-calendar2-plus-fill me-2"></i> Kunjungan Terdaftar Hari Ini
            </h5>
        </div>

        <div class="d-flex flex-wrap gap-3 align-items-center px-4 py-3 bg-light border-bottom">
            <select id="dropdownDokter" class="form-select form-select-sm w-auto">
                <option value="all">Semua Dokter</option>
                @foreach ($listDokter as $dokter)
                    <option value="{{ $dokter->Nama_Dokter }}">{{ $dokter->Nama_Dokter }}</option>
                @endforeach
            </select>
        </div>

        <div class="card-body p-0">
            @if($data->total() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 text-center">
                        <thead class="bg-light small text-muted">
                            <tr>
                                <th>No</th>
                                <th>Nama Pasien</th>
                                <th>No Telepon</th>
                                <th>No Urut</th>
                                <th>Keluhan</th>
                                <th>Dokter</th>
                                <th>Layanan</th>
                                <th>Ruangan</th>
                                <th>Jadwal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $k)
                            <tr data-nama-dokter="{{ $k->dokter->Nama_Dokter ?? 'Belum ditentukan' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center justify-content-center">
                                        <div class="rounded-circle bg-secondary bg-opacity-10 p-2 me-2">
                                            <i class="bi bi-person text-secondary"></i>
                                        </div>
                                        <span class="fw-semibold text-dark">{{ $k->Nama_Pasien }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-pill px-3">
                                        {{ $k->pasien->No_Tlp ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold">
                                        {{ str_pad($k->Nomor_Urut, 3, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-pill px-3">
                                        {{ $k->Keluhan }}
                                    </span>
                                </td>
                                <td>
                                    {{ $k->dokter->Nama_Dokter ?? 'Belum ditentukan' }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-pill px-3">
                                        {{ $k->layanan->Nama_Layanan ?? 'Tidak ada layanan' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-pill px-3">
                                        {{ $k->ruangan->Nama_Ruangan ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-pill px-3">
                                        {{ $k->Jadwal }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = match($k->Status) {
                                            'Selesai' => 'success',
                                            'Selesai ' => 'success',
                                            'Diperiksa' => 'info',
                                            'menunggu' => 'primary',
                                            'Belum Hadir' => 'danger',
                                            default => 'secondary'
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $statusClass }}">{{ $k->Status }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3 px-3">
                        {{ $data->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-calendar-x fa-3x text-muted mb-3"></i>
                    <h6 class="text-muted">Belum ada kunjungan</h6>
                    <p class="text-muted small mb-0">Kunjungan akan muncul di sini ketika pasien mendaftar</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdown = document.getElementById('dropdownDokter');
        const rows = document.querySelectorAll('tbody tr');

        dropdown.addEventListener('change', function () {
            const selected = this.value.toLowerCase();

            rows.forEach(row => {
                const dokter = row.getAttribute('data-nama-dokter')?.toLowerCase() ?? '';
                if (selected === 'all' || dokter === selected) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>

@endsection
