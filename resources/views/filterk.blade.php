@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Filter Kunjungan</h3>

    <form method="GET" action="{{ route('filterk') }}" class="row g-3 mb-4">
        <div class="col-md-3">
            <label>Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-3">
            <label>Dokter</label>
            <select name="dokter" class="form-control">
                <option value="">-- Semua Dokter --</option>
                @foreach($dokterList as $dokter)
                    <option value="{{ $dokter->Id_Dokter }}" {{ request('dokter') == $dokter->Id_Dokter ? 'selected' : '' }}>
                        {{ $dokter->Nama_Dokter }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label>Sesi</label>
            <select name="sesi" class="form-control">
                <option value="">-- Semua Sesi --</option>
                @foreach($sesiList as $sesi)
                    <option value="{{ $sesi->Id_Sesi }}" {{ request('sesi') == $sesi->Id_Sesi ? 'selected' : '' }}>
                        {{ $sesi->Nama_Sesi }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="">-- Semua Status --</option>
                <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="Terproses" {{ request('status') == 'Terproses' ? 'selected' : '' }}>Terproses</option>
                <option value="Tidak Selesai" {{ request('status') == 'Tidak Selesai' ? 'selected' : '' }}>Tidak Selesai</option>
            </select>
        </div>
        <div class="col-12">
            <button class="btn btn-primary">Filter</button>
            <a href="{{ route('filterk') }}" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <h5>Hasil: {{ $kunjungan->count() }} kunjungan</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Pasien</th>
                <th>Dokter</th>
                <th>Sesi</th>
                <th>Jadwal Kedatangan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kunjungan as $item)
                <tr>
                    <td>{{ $item->pasien->Nama_Pasien ?? '-' }}</td>
                    <td>{{ $item->sesi->dokter->Nama_Dokter ?? '-' }}</td>
                    <td>{{ $item->sesi->Nama_Sesi ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->Jadwal_Kedatangan)->format('d M Y H:i') }}</td>
                    <td>{{ $item->Status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Tidak ada data kunjungan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
