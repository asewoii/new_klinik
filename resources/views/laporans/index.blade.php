@extends('layouts.nav_admin') 

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Laporan Kunjungan Pasien</h4>

    {{-- FORM FILTER --}}
    <form action="{{ route('laporans.filter') }}" method="GET" class="row g-3">
        <div class="col-md-4">
            <label for="tanggal" class="form-label">Tanggal Registrasi</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ request('tanggal') }}">
        </div>

        <div class="col-md-4">
            <label for="status" class="form-label">Status Kunjungan</label>
            <select name="status" id="status" class="form-select">
                <option value="">--- Semua ---</option>
                <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                <option value="diperiksa" {{ request('status') == 'diperiksa' ? 'selected' : '' }}>Diperiksa</option>
                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
            </select>
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">View Export</button>
            @if (!empty($data) && count($data))
                <a href="{{ route('laporans.download', request()->query()) }}" class="btn btn-success">Download PDF</a>
            @endif

            @if (!empty($data) && count($data))
                <a href="{{ route('laporans.preview', request()->query()) }}" target="_blank" class="btn btn-secondary me-2">
                    👁 Preview PDF
                </a>
            @endif
        </div>
    </form>

    {{-- TABEL LAPORAN --}}
    @if (!empty($data) && count($data))
        <div class="table-responsive mt-4">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Pasien</th>
                        <th>Dokter</th>
                        <th>Ruangan</th>
                        <th>Layanan</th>
                        <th>Tanggal Registrasi</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $i => $kunjungan)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $kunjungan->Nama_Pasien }}</td>
                            <td>{{ $kunjungan->dokter->Nama_Dokter ?? '-' }}</td>
                            <td>{{ $kunjungan->ruangan->Nama_Ruangan ?? '-' }}</td>
                            <td>{{ $kunjungan->layanan->Nama_Layanan ?? '-' }}</td>
                            <td>{{ $kunjungan->Tanggal_Registrasi }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ ucfirst($kunjungan->Status) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @elseif(request()->has('tanggal') || request()->has('status'))
        <div class="alert alert-warning mt-4">Tidak ada data ditemukan untuk filter yang dipilih.</div>
    @endif
</div>
@endsection
