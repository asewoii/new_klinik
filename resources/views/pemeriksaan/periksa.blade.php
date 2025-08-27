@extends('layouts.nav_admin')
@section('title', 'Diperiksa Hari Ini')

@section('content')
<div class="container">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center px-3 py-3">
            <h5 class="mb-0 fw-bold text-dark">
                <i class="bi bi-calendar2-plus-fill me-2"></i> Diperiksa Hari Ini
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
                                <th>Dokter</th>
                                <th>Layanan</th>
                                <th>Pasien</th>
                                <th>No Telepon</th>
                                <th>Keluhan</th>
                                <th>Tombol</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $k)
                            <tr data-nama-dokter="{{ $k->dokter->Nama_Dokter ?? 'Belum ditentukan' }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    {{ $k->dokter->Nama_Dokter ?? 'Belum ditentukan' }}
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-pill px-3">
                                        {{ $k->indikasi->deskripsi ?? 'Tidak ada Layanan' }}
                                    </span>
                                </td>
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
                                        {{ $k->pasien->No_Tlp ??'-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark rounded-pill px-3">
                                        {{ $k->Keluhan }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('pemeriksaan.create', ['pasien' => $k->Id_Pasien]) }}" class="btn btn-sm btn-primary">Periksa</a>
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
                    <p class="text-muted small mb-0">Belum ada pasien di periksa hari ini</p>
                </div>
            @endif
        </div>
    </div>
</div>


<script>
    document.getElementById('dropdownDokter').addEventListener('change', function () {
        const selectedDokter = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const namaDokter = row.getAttribute('data-nama-dokter')?.toLowerCase() || '';

            if (selectedDokter === 'all' || namaDokter.includes(selectedDokter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>

@endsection
