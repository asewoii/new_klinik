@extends('layouts.nav_admin')
@section('title', 'Laporan Pemeriksaan')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">Laporan Pemeriksaan</h4>

    <!-- Form Filter -->
    <form method="GET" action="{{ route('pemeriksaan.laporan') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
            <input type="date" name="tanggal_awal" id="tanggal_awal" class="form-control" value="{{ request('tanggal_awal') }}">
        </div>
        <div class="col-md-3">
            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
        </div>
        <div class="col-md-3">
            <label for="dokter_id" class="form-label">Dokter</label>
            <select name="dokter_id" id="dokter_id" class="form-select">
                <option value="">-- Semua Dokter --</option>
                @foreach($listDokter as $d)
                    <option value="{{ $d->Id_Dokter }}" {{ request('dokter_id') == $d->Id_Dokter ? 'selected' : '' }}>
                        {{ $d->Nama_Dokter }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
        </div>
        
    </form>

    <!-- Hasil Tabel -->
    @if($data->count())
    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pasien</th>
                <th>Dokter</th>
                <th>Diagnosa</th>
                <th>Tindakan</th>
                <th>Resep</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($item->Tanggal_Pemeriksaan)->format('d/m/Y') }}</td>
                <td>{{ $item->kunjungan->pasien->Nama_Pasien ?? '-' }}</td>
                <td>{{ $item->dokter->Nama_Dokter }}</td>
                <td style="white-space: pre-wrap;">{{ $item->Diagnosa }}</td>
                <td style="white-space: pre-wrap;">{{ $item->Tindakan }}</td>
                <td style="white-space: pre-wrap;">{{ $item->Resep }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <div class="alert alert-info">Tidak ada data pemeriksaan ditemukan.</div>
    @endif
</div>
@endsection
