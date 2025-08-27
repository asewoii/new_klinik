@extends('layouts.nav_admin')
@section('title', 'Laporan Data')

@section('content')
<div class="container mt-3">
    <h4 class="mb-4">Laporan Data Klinik</h4>

    <form method="GET" action="{{ route('laporan.index') }}" class="mb-4">
        <label for="jenis" class="form-label">Pilih Jenis Laporan:</label>
        <div class="d-flex gap-3">
            <select name="jenis" id="jenis" class="form-select" style="max-width: 300px;">
                <option value="kunjungan" {{ $jenis == 'kunjungan' ? 'selected' : '' }}>Kunjungan</option>
                <option value="pemeriksaan" {{ $jenis == 'pemeriksaan' ? 'selected' : '' }}>Pemeriksaan</option>
                <option value="pasien" {{ $jenis == 'pasien' ? 'selected' : '' }}>Pasien</option>
                <option value="dokter" {{ $jenis == 'dokter' ? 'selected' : '' }}>Dokter</option>
                <option value="ruangan" {{ $jenis == 'ruangan' ? 'selected' : '' }}>Ruangan</option>
            </select>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </div>
    </form>

<!--Kunjungan-->
    @if ($jenis == 'kunjungan')
            <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 mb-4">
                <input type="hidden" name="jenis" value="kunjungan">

                <div class="col-md-3">
                    <label for="from" class="form-label">Dari Tanggal</label>
                    <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
                </div>

                <div class="col-md-3">
                    <label for="to" class="form-label">Sampai Tanggal</label>
                    <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
                </div>

                <div class="col-md-3">
                    <label for="dokter" class="form-label">Dokter</label>
                    <select name="dokter" id="dokter" class="form-select">
                        <option value="">-- Semua Dokter --</option>
                        @foreach ($dokters as $dokter)
                            <option value="{{ $dokter->Nama_Dokter }}" {{ request('dokter') == $dokter->Nama_Dokter ? 'selected' : '' }}>
                                {{ $dokter->Nama_Dokter }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="ruangan" class="form-label">Ruangan</label>
                    <select name="ruangan" id="ruangan" class="form-select">
                        <option value="">-- Semua Ruangan --</option>
                        @foreach ($ruangans as $ruangan)
                            <option value="{{ $ruangan->Id_Ruangan }}" {{ request('ruangan') == $ruangan->Id_Ruangan ? 'selected' : '' }}>
                                {{ $ruangan->Nama_Ruangan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="layanan" class="form-label">Layanan</label>
                    <select name="layanan" id="layanan" class="form-select">
                        <option value="">-- Semua Layanan --</option>
                        @foreach ($layanan as $ind)
                            <option value="{{ $ind->Id_Layanan }}" {{ request('layanan') == $ind->Id_Layanan ? 'selected' : '' }}>
                                {{ $ind->Nama_Layanan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">-- Semua --</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="diperiksa" {{ request('status') == 'diperiksa' ? 'selected' : '' }}>Diperiksa</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="belum hadir" {{ request('status') == 'belum hadir' ? 'selected' : '' }}>Belum Hadir</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="per_page" class="form-label">Data per Halaman</label>
                    <select name="per_page" id="per_page" class="form-select" onchange="this.form.submit()">
                        @foreach ([10, 25, 50, 100] as $limit)
                            <option value="{{ $limit }}" {{ request('per_page', 10) == $limit ? 'selected' : '' }}>
                                {{ $limit }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>

                @if ($jenis == 'kunjungan' && count($data))
                    <div class="mb-3 d-flex gap-2">
                        <a href="{{ route('laporan.export.pdf', request()->query()) }}" class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                        <a href="{{ route('laporan.export.excel', request()->query()) }}" class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                    </div>
                @endif

            </form>
@endif
<!--PEMERIKSAAN-->
    @if ($jenis == 'pemeriksaan')
        <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 mb-4">
            <input type="hidden" name="jenis" value="pemeriksaan">

            <div class="col-md-3">
                <label for="from" class="form-label">Dari Tanggal</label>
                <input type="date" name="from" id="from" class="form-control" value="{{ request('from') }}">
            </div>

            <div class="col-md-3">
                <label for="to" class="form-label">Sampai Tanggal</label>
                <input type="date" name="to" id="to" class="form-control" value="{{ request('to') }}">
            </div>

            <div class="col-md-3">
                <label for="dokter" class="form-label">Dokter</label>
                <select name="dokter" id="dokter" class="form-select">
                    <option value="">-- Semua Dokter --</option>
                    @foreach ($dokters as $dokter)
                        <option value="{{ $dokter->Id_Dokter }}" {{ request('dokter') == $dokter->Id_Dokter ? 'selected' : '' }}>
                            {{ $dokter->Nama_Dokter }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label for="search" class="form-label">Kata Kunci (Diagnosa / Tindakan)</label>
                <input type="text" name="search" class="form-control" placeholder="Cari..." value="{{ request('search') }}">
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">Filter</button>
            </div>

             <div class="mb-3 d-flex gap-2">
                <a href="{{ route('laporan.pemeriksaan.export.pdf', request()->query()) }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <a href="{{ route('laporan.pemeriksaan.export.excel', request()->query()) }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
            </div>

            <div class="col-md-2">
                <label for="per_page" class="form-label">Data per Halaman</label>
                <select name="per_page" id="per_page" class="form-select" onchange="this.form.submit()">
                    @foreach ([10, 25, 50, 100] as $limit)
                        <option value="{{ $limit }}" {{ request('per_page', 10) == $limit ? 'selected' : '' }}>
                            {{ $limit }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>
@endif
<!--PASIEN-->
    @if ($jenis == 'pasien')
    <form method="GET" class="row g-3 mb-4" action="{{ route('laporan.index') }}">
        <input type="hidden" name="jenis" value="pasien">

        <div class="col-md-3">
            <label>Dari Tanggal</label>
            <input type="date" name="from" class="form-control" value="{{ request('from') }}">
        </div>

        <div class="col-md-3">
            <label>Sampai Tanggal</label>
            <input type="date" name="to" class="form-control" value="{{ request('to') }}">
        </div>

        <div class="col-md-2">
            <label>Jenis Kelamin</label>
            <select name="jk" class="form-select">
                <option value="">-- Semua --</option>
                <option value="L" {{ request('jk') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                <option value="P" {{ request('jk') == 'P' ? 'selected' : '' }}>Perempuan</option>
            </select>
        </div>

        <div class="col-md-3">
            <label>Cari Nama / NIK</label>
            <input type="text" name="keyword" class="form-control" placeholder="Nama atau NIK" value="{{ request('keyword') }}">
        </div>

        <div class="col-md-2">
            <label>Umur Minimum</label>
            <input type="number" name="umur_min" class="form-control" value="{{ request('umur_min') }}">
        </div>

        <div class="col-md-2">
            <label>Umur Maksimum</label>
            <input type="number" name="umur_max" class="form-control" value="{{ request('umur_max') }}">
        </div>

        <div class="col-md-1 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>

        <div class="mb-3 d-flex gap-2">
            <a href="{{ route('laporan.pasien.export.pdf', request()->query()) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('laporan.pasien.export.excel', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>

        <div class="col-md-2">
            <label for="per_page" class="form-label">Data per Halaman</label>
            <select name="per_page" id="per_page" class="form-select" onchange="this.form.submit()">
                @foreach ([10, 25, 50, 100] as $limit)
                    <option value="{{ $limit }}" {{ request('per_page', 10) == $limit ? 'selected' : '' }}>
                        {{ $limit }}
                    </option>
                @endforeach
            </select>
        </div>

    </form>
@endif
<!--DOKTER-->
@if ($jenis == 'dokter')
    <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 mb-4">
        <input type="hidden" name="jenis" value="dokter">

        {{-- Filter Ruangan --}}
        <div class="col-md-3">
            <label for="ruanganSelect" class="form-label">Pilih Ruangan</label>
            <select name="ruangan[]" id="ruanganSelect" class="form-select" multiple>
                @foreach ($ruangans as $ruang)
                    <option value="{{ $ruang->Nama_Ruangan }}"
                        {{ collect(request('ruangan', []))->contains($ruang->Id_Ruangan) ? 'selected' : '' }}>
                        {{ $ruang->Nama_Ruangan }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Filter Hari --}}
        <div class="col-md-5">
            <label class="form-label d-block">Hari</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach (['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'] as $hari)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="hari[]" value="{{ $hari }}"
                            {{ is_array(request('hari')) && in_array($hari, request('hari')) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $hari }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Filter Sesi --}}
        <div class="col-md-4">
            <label class="form-label d-block">Sesi</label>
            <div class="d-flex flex-wrap gap-2">
                @foreach (['Pagi', 'Siang', 'Sore'] as $sesi)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="sesi[]" value="{{ $sesi }}"
                            {{ is_array(request('sesi')) && in_array($sesi, request('sesi')) ? 'checked' : '' }}>
                        <label class="form-check-label">{{ $sesi }}</label>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Filter Nama / Spesialis --}}
        <div class="col-md-3">
            <label for="keyword" class="form-label">Nama / Spesialis</label>
            <input type="text" name="keyword" class="form-control"
                value="{{ request('keyword') }}" placeholder="Cari dokter atau spesialis">
        </div>

        {{-- Filter Per Page --}}
        <div class="col-md-2">
            <label for="per_page" class="form-label">Baris per Halaman</label>
            <select name="per_page" class="form-select" onchange="this.form.submit()">
                @foreach ([10, 25, 50] as $val)
                    <option value="{{ $val }}"
                        {{ request('per_page', 10) == $val ? 'selected' : '' }}>
                        {{ $val }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Tombol Filter --}}
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
        </div>

        <div class="d-flex mb-3 gap-2">
            <a href="{{ route('laporan.dokter.pdf', request()->query()) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf me-1"></i> Export PDF
            </a>
            <a href="{{ route('laporan.dokter.excel', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
        </div>
    </form>
@endif
<!--RUANGAN-->
@if ($jenis === 'ruangan')
    <form method="GET" action="{{ route('laporan.index') }}" class="row g-3 mb-4">
        <input type="hidden" name="jenis" value="ruangan">

        {{-- Dropdown Nama Ruangan --}}
        <div class="col-md-3">
            <select name="ruangan" class="form-select">
                <option value="">-- Semua Ruangan --</option>
                @foreach($ruangans as $r)
                    <option value="{{ $r->Id_Ruangan }}" {{ request('ruangan') == $r->Id_Ruangan ? 'selected' : '' }}>
                        {{ $r->Nama_Ruangan }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Jenis Ruangan --}}
        <div class="col-md-2">
            <select name="jenis_ruangan" class="form-select">
                <option value="">-- Jenis Ruangan --</option>
                <option value="Layanan" {{ request('jenis_ruangan') == 'Layanan' ? 'selected' : '' }}>Layanan</option>
                <option value="IGD" {{ request('jenis_ruangan') == 'IGD' ? 'selected' : '' }}>IGD</option>
                <option value="Rawat Inap" {{ request('jenis_ruangan') == 'Rawat Inap' ? 'selected' : '' }}>Rawat Inap</option>
            </select>
        </div>

        {{-- Lantai --}}
        <div class="col-md-2">
            <input type="text" name="lantai" class="form-control" placeholder="Lantai" value="{{ request('lantai') }}">
        </div>

        {{-- Status --}}
        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">-- Semua Status --</option>
                <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="Tidak Aktif" {{ request('status') == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>

        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
        </div>

        <div class="d-flex gap-2 mb-3">
            <a href="{{ route('laporan.ruangan.pdf', request()->query()) }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>
            <a href="{{ route('laporan.ruangan.excel', request()->query()) }}" class="btn btn-success">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>

    </form>
@endif




<!--=================-->
@if(count($data))
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    @foreach(array_keys($data[0]->getAttributes()) as $field)
                        <th>{{ $field }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $d)
                    <tr>
                        @foreach($d->getAttributes() as $value)
                            <td>{{ is_array($value) ? json_encode($value) : $value }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-muted">Tidak ada data untuk laporan ini.</p>
@endif

@if ($jenis == 'kunjungan')
        <div class="mt-3">
            {{ $data->links() }}
        </div>
@endif
@if ($jenis == 'pemeriksaan')
        <div class="mt-3">
            {{ $data->links() }}
        </div>
        
@endif
@if ($jenis == 'pasien')
    
    <div class="mt-3">
    {{ $data->links() }}
    </div>
@endif
@if ($jenis == 'dokter')
    <div class="mt-3">
            {{ $data->links() }}
        </div>
    </div>
@endif
@if ($jenis === 'ruangan')
    <div class="mt-3">
        {{ $data->links() }}
    </div>
@endif

@endsection
