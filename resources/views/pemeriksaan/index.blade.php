@extends('layouts.nav_admin')
@section('title', 'Data Pemeriksaan')

@section('content')
<div class="container mt-3">
    <h4>Data Hasil Pemeriksaan</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Dropdown Filter Dokter --}}
    <div class="d-flex flex-wrap gap-3 align-items-center px-4 py-3 bg-light border-bottom">
        <select id="dropdownDokter" class="form-select form-select-sm w-auto">
            <option value="all"> Semua Dokter </option>
            @foreach ($listDokter as $dokter)
                <option value="{{ $dokter->Nama_Dokter }}">{{ $dokter->Nama_Dokter }}</option>
            @endforeach
        </select>
    </div>
    <div class="d-flex justify-content-end my-3">
        <a href="{{ route('pemeriksaan.laporan') }}" class="btn btn-outline-primary">
            <i class="fas fa-file-medical-alt me-1"></i> Laporan Pemeriksaan
        </a>
    </div>

    {{-- Tabel Pemeriksaan --}}
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
            <thead class="table-light">
                <tr>
                    <th>Tanggal</th>
                    <th>Jam</th>
                    <th>Pasien</th>
                    <th>Keluhan</th>
                    <th>Dokter</th>
                    <th>Tindakan</th>
                    <th>Resep</th>
                    <th>Diagnosa</th>
                    <th>Catatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                <tr data-nama-dokter="{{ strtolower($row->dokter->Nama_Dokter ?? '') }}">
                    <td>{{ $row->Tanggal_Pemeriksaan }}</td>
                    <td>{{ $row->Jam_Pemeriksaan }}</td>
                    <td>{{ $row->kunjungan->Nama_Pasien ?? '-' }}</td>
                    <td>{{ $row->kunjungan->Keluhan ?? '-' }}</td>
                    <td>{{ $row->dokter->Nama_Dokter ?? '-' }} ({{ $row->dokter->Spesialis ?? '-' }})</td>
                    <td>{{ $row->Tindakan }}</td>
                    <td>{{ $row->Resep }}</td>
                    <td>{{ $row->Diagnosa }}</td>
                    <td>{{ $row->Catatan }}</td>
                    <td>
                        <a href="{{ route('pemeriksaan.edit', $row->Id_Pemeriksaan) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('pemeriksaan.destroy', $row->Id_Pemeriksaan) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- JavaScript Filter Dokter --}}
<script>
    document.getElementById('dropdownDokter').addEventListener('change', function () {
        const selectedDokter = this.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            const namaDokter = row.getAttribute('data-nama-dokter');
            if (selectedDokter === 'all' || namaDokter.includes(selectedDokter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
</script>
@endsection
