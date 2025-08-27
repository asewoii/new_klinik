@extends('layouts.admin')

@section('dashboard')
    <div class="row g-4">
        <!-- ============================== KIRI ============================== -->
        <div class="col-xl-3 col-lg-2">
            <div class="dashboard-sidebar">
                <!-- === NO ANTRIAN (Nomor Panggilan Saat Ini) === -->
                <div class="card border-0 shadow-sm hover-lift overflow-hidden mb-3">
                    <div class="judulcard card-header text-center py-3">
                        <h4 class="mb-0">Nomor Antrian </h4>
                    </div>
                    <div class="isicard card-body text-center">
                        @if (is_null($pasienYangBolehDitampilkan))
                            <div class="fs-5 text-muted">Tidak ada antrian</div>
                        @else
                            <div class="card border-0 rounded shadow-sm bg-white p-3">
                                <div class="text_judul mb-3 text-center">
                                    <div class=" fs-2 fw-bold text-primary">
                                        {{ str_pad($pasienYangBolehDitampilkan->Nomor_Urut, 3, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <!--small class=" text-muted">Nomor Antrian</!--small-->
                                </div>

                                <div class="text_deskripsi mb-3">
                                    <div class="bagi2">
                                        <span class="kiri_text_deskripsi text-secondary">
                                            <i class="fa-solid fa-address-card"></i>
                                            <strong>Pasien</strong>
                                        </span>
                                        <span class="kanan_text_deskripsi">:
                                            {{ $pasienYangBolehDitampilkan->Nama_Pasien }}</span>
                                    </div>
                                </div>

                                <div class="text_deskripsi mb-3">
                                    <div class="bagi2">
                                        <span class="kiri_text_deskripsi text-secondary">
                                            <i class="fa-solid fa-stethoscope"></i>
                                            <strong>Layanan</strong>
                                        </span>
                                        <span class="kanan_text_deskripsi">:
                                            {{ $pasienYangBolehDitampilkan->layanan->Nama_Layanan ?? '-' }}</span>
                                    </div>
                                </div>

                                <div class="text_deskripsi">
                                    <div class=" bagi2">
                                        <span class="kiri_text_deskripsi text-secondary">
                                            <i class="fa-solid fa-user-doctor"></i>
                                            <strong>Dokter</strong>
                                        </span>
                                        <span class="kanan_text_deskripsi">:
                                            {{ $pasienYangBolehDitampilkan->dokter->Nama_Dokter ?? '-' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- === TOTAL KUNJUNGAN HARI INI === -->
                <div class="card cursor_pointer border-0 shadow-sm hover-lift mb-3" data-bs-toggle="modal"
                    data-bs-target="#modalKunjunganHariIni">
                    <div class="shadow_card card-body p-4">
                        <div class="d-flex align-items-center">
                            <!-- Icon Lingkaran -->
                            <div class="icon_card bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-users fa-lg text-primary"></i>
                            </div>

                            <!-- Konten Teks -->
                            <div class="flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1 fs-4">
                                    {{ $kunjunganHariIniDetail->total() }}
                                </h6>
                                <span class="small text-muted">Total Kunjungan</span>
                            </div>


                        </div>
                    </div>
                </div>




                <!-- === TOTAL PASIEN HARI INI === -->
                <div class="card border-0 shadow-sm hover-lift mb-3" data-bs-toggle="modal"
                    data-bs-target="#modalPasienHariIni">
                    <div class="shadow_card card-body p-4">
                        <div class="d-flex align-items-center">
                            <!-- Icon Lingkaran -->
                            <div class="icon_card bg-info bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-user-plus fa-lg text-success"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1 fs-4">{{ $pasienHariIni->count() }}</h6>
                                <span class="small text-muted">Total Pasien Daftar</span>
                            </div>
                            <!--i class="fas fa-chevron-right text-muted"></!--i-->
                        </div>
                    </div>
                </div>

                <!-- === GRAFIK LAYANAN TERBANYAK === -->
                <div class="card border-1 shadow-sm mb-3 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom">
                        <label for="filterLayanan" class="form-label fw-semibold mb-0">
                            📊 Chart Layanan
                        </label>
                        <select id="filterLayanan"
                            class="form-select form-select-sm w-auto shadow-sm rounded-3 border-primary mb-1">
                            <option value="hari" selected>📅 Hari Ini</option>
                            <option value="bulan">📆 Bulan Ini</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <canvas id="layananChart" height="250"></canvas>
                    </div>
                </div>

                <!-- === GRAFIK UMUR === -->
                <div class="card border-1 shadow-sm mb-3 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom">
                        <label for="filterUmur" class="form-label fw-semibold mb-0">
                            📊 Chart Umur
                        </label>
                        <select id="filterUmur"
                            class="form-select form-select-sm w-auto shadow-sm rounded-3 border-primary mb-1">
                            <option value="hari" selected>📅 Hari Ini</option>
                            <option value="bulan">📆 Bulan Ini</option>
                            <option value="tahun">📆 Tahun Ini</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <canvas id="UmurChart" height="250"></canvas>
                    </div>
                </div>

                <!-- === GRAFIK JENIS KELAMIN === -->
                <div class="card border-1 shadow-sm mb-3 p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3 border-bottom">
                        <label for="filterJK" class="form-label fw-semibold mb-0">
                            📊 Chart Jenis Kelamin
                        </label>
                        <select id="filterJK"
                            class="form-select form-select-sm w-auto shadow-sm rounded-3 border-primary mb-1">
                            <option value="hari" selected>📅 Hari Ini</option>
                            <option value="bulan">📆 Bulan Ini</option>
                            <option value="tahun">📆 Tahun Ini</option>
                        </select>
                    </div>

                    <div class="pt-2">
                        <canvas id="JKChart" height="250"></canvas>
                    </div>
                </div>

                <!-- === GRAFIK UMUR KUNJUNGAN === -->
                <div class="card cursor_pointer border-0 shadow-sm hover-lift mb-3">
                    <div class="shadow_card card-body py-2 px-2">
                        <div class="d-flex align-items-center justify-center mb-3">
                            <!-- Icon Lingkaran -->
                            <div class="icon_card bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="bi bi-bar-chart-line-fill fa-lg text-primary"></i>
                            </div>

                            <div class="flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1 fs-6">
                                    Grafik Umur Pasien Hari Ini
                                </h6>
                            </div>
                        </div>
                        <canvas id="umurChart" height="120"></canvas>
                    </div>
                </div>

                <!-- === GRAFIK UMUR KUNJUNGAN === -->
                <div class="card cursor_pointer border-0 shadow-sm hover-lift mb-3">
                    <div class="shadow_card card-body py-2 px-2">
                        <div class="d-flex align-items-center justify-center mb-3">
                            <!-- Icon Lingkaran -->
                            <div class="icon_card bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="bi bi-bar-chart-line-fill fa-lg text-primary"></i>
                            </div>

                            <div class="flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1 fs-6">
                                    Grafik Layanan Pasien Hari Ini
                                </h6>
                            </div>
                        </div>
                        <canvas id="layananChart" width="400" height="400"></canvas>
                    </div>
                </div>

                <!-- === GRAFIK GENDER === -->
                <div class="card cursor_pointer border-0 shadow-sm hover-lift">
                    <div class="shadow_card card-body py-2 px-2">
                        <div class="d-flex align-items-center">
                            <!-- Icon Lingkaran -->
                            <div class="icon_card bg-opacity-10 rounded-circle p-3 me-3">
                                <i class="fas fa-venus-mars fa-lg text-info"></i>
                            </div>

                            <!-- Konten Teks -->
                            <div class="flex-grow-1">
                                <h6 class="fw-bold text-dark mb-1 fs-6">
                                    Grafik Jenis Kelamin Pasien Hari Ini
                                </h6>
                                <span class="small text-muted">Total Kunjungan</span>
                            </div>
                        </div>
                        <canvas id="genderChart" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <!-- ============================== /KIRI ============================== -->

        <!-- ============================== KANAN ============================== -->
        <div class="col-xl-9 col-lg-8">
            <!--JADWAL DOKTER HARI INI-->
            <div class="card mb-1">
                <div class="shadow border-1 rounded-4 h-100">
                    <div
                        class="wbirus rounded-top-4 px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                        <h5 class="fw-bold text-white mb-0">
                            <i class="fas fa-calendar-check me-2"></i>
                            Jadwal Dokter Hari ( {{ ucfirst(now()->locale('id')->translatedFormat('l')) }} )
                        </h5>

                        <!-- Filter dan Refresh ( Belom dibuat ) -->
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="table"
                            class="custom-table table table-bordered table-hover table-striped align-middle text-center w-100">
                            <thead class="table-primary text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dokter</th>
                                    <th>Spesialis</th>
                                    <th>Ruang</th>
                                    <th>Sesi</th>
                                    <th>Jam Praktik</th>
                                    <th>Kuota</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grouped = collect($jadwalDokterHariIni)->groupBy('nama_dokter');
                                    $no = 1;
                                @endphp

                                @foreach ($grouped as $namaDokter => $jadwalList)
                                    @foreach ($jadwalList as $i => $jadwal)
                                        <tr>
                                            @if ($i === 0)
                                                <td rowspan="{{ $jadwalList->count() }}"
                                                    class="text-center align-middle fw-semibold">
                                                    {{ $no++ }}
                                                </td>
                                                <td rowspan="{{ $jadwalList->count() }}"
                                                    class="text-center align-middle fw-semibold text-primary">
                                                    {{ $namaDokter }}
                                                </td>
                                                <td rowspan="{{ $jadwalList->count() }}"
                                                    class="text-center align-middle fw-semibold">
                                                    {{ $jadwal['spesialis'] }}
                                                </td>
                                            @endif

                                            <td>
                                                <span class="badge bg-secondary">{{ $jadwal['ruang'] }}</span>
                                            </td>

                                            <td>
                                                @php
                                                    $daftarSesi = explode(',', $jadwal['sesi']);
                                                    $warnaSesi = [
                                                        'Pagi' => 'bg-success',
                                                        'Siang' => 'bg-warning text-dark',
                                                        'Sore' => 'bg-primary',
                                                    ];
                                                @endphp
                                                @foreach ($daftarSesi as $sesi)
                                                    <span
                                                        class="badge {{ $warnaSesi[trim($sesi)] ?? 'bg-secondary' }} me-1 mb-1">
                                                        {{ trim($sesi) }}
                                                    </span>
                                                @endforeach
                                            </td>

                                            <td class="fw-semibold">
                                                {{ $jadwal['jam_mulai'] }} - {{ $jadwal['jam_selesai'] }}
                                            </td>

                                            <td>
                                                <div class="d-grid gap-1">
                                                    <!-- Total -->
                                                    <span class="badge bg-light text-dark border border-secondary"
                                                        title="Kuota maksimal">
                                                        <i class="bi bi-person-lines-fill me-1"></i> Total:
                                                        {{ $jadwal['kuota'] }}
                                                    </span>

                                                    <!-- Terpakai -->
                                                    <span class="badge bg-warning text-dark" title="Sudah mendaftar">
                                                        <i class="bi bi-person-check-fill me-1"></i> Terpakai:
                                                        {{ $jadwal['terpakai'] }}
                                                    </span>

                                                    <!-- Sisa -->
                                                    @php
                                                        $sisa = $jadwal['sisa'];
                                                        $warnaSisa =
                                                            $sisa == 0
                                                                ? 'bg-danger'
                                                                : ($sisa <= 3
                                                                    ? 'bg-warning text-dark'
                                                                    : 'bg-success');
                                                        $ikonSisa =
                                                            $sisa == 0
                                                                ? 'bi-x-circle-fill'
                                                                : ($sisa <= 3
                                                                    ? 'bi-exclamation-circle-fill'
                                                                    : 'bi-check-circle-fill');
                                                        $tooltipSisa = $sisa == 0 ? 'Kuota penuh' : 'Masih tersedia';
                                                    @endphp
                                                    <span class="badge {{ $warnaSisa }}" title="{{ $tooltipSisa }}">
                                                        <i class="bi {{ $ikonSisa }} me-1"></i> Sisa:
                                                        {{ $sisa }}
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="table_dashboard_responsive mb-1 d-none">
                <h5 class="wbirus fw-bold text-dark mb-4">
                    <i class="bi bi-calendar-week me-2"></i>
                    Jadwal Dokter Hari Ini ({{ ucfirst(now()->locale('id')->translatedFormat('l')) }})
                </h5>

                <div class="card border-0 shadow-sm rounded-3 mb-4 p-4">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle text-center table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dokter</th>
                                    <th>Spesialis</th>
                                    <th>Ruangan</th>
                                    <th>Sesi</th>
                                    <th>Jam Praktik</th>
                                    <th>Kuota</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grouped = collect($jadwalDokterHariIni)->groupBy('nama_dokter');
                                    $no = 1;
                                @endphp

                                @foreach ($grouped as $namaDokter => $jadwalList)
                                    @foreach ($jadwalList as $i => $jadwal)
                                        <tr>
                                            @if ($i === 0)
                                                <td rowspan="{{ $jadwalList->count() }}" class="fw-semibold">
                                                    {{ $no++ }}</td>
                                                <td rowspan="{{ $jadwalList->count() }}" class="fw-semibold">
                                                    {{ $namaDokter }}</td>
                                                <td rowspan="{{ $jadwalList->count() }}" class="fw-semibold">
                                                    {{ $jadwal['spesialis'] }}</td>
                                            @endif

                                            <td>
                                                <span class="badge bg-secondary">{{ $jadwal['ruang'] }}</span>
                                            </td>

                                            <td>
                                                @php
                                                    $daftarSesi = explode(',', $jadwal['sesi']);
                                                    $warnaSesi = [
                                                        'Pagi' => 'bg-success',
                                                        'Siang' => 'bg-warning text-dark',
                                                        'Sore' => 'bg-primary',
                                                    ];
                                                @endphp
                                                @foreach ($daftarSesi as $sesi)
                                                    <span
                                                        class="badge {{ $warnaSesi[trim($sesi)] ?? 'bg-secondary' }} me-1 mb-1">
                                                        {{ trim($sesi) }}
                                                    </span>
                                                @endforeach
                                            </td>

                                            <td class="fw-semibold">
                                                {{ $jadwal['jam_mulai'] }} - {{ $jadwal['jam_selesai'] }}
                                            </td>

                                            <td>
                                                <div class="d-grid gap-1">
                                                    <!-- Total -->
                                                    <span class="badge bg-light text-dark border border-secondary"
                                                        title="Kuota maksimal">
                                                        <i class="bi bi-person-lines-fill me-1"></i> Total:
                                                        {{ $jadwal['kuota'] }}
                                                    </span>

                                                    <!-- Terpakai -->
                                                    <span class="badge bg-warning text-dark" title="Sudah mendaftar">
                                                        <i class="bi bi-person-check-fill me-1"></i> Terpakai:
                                                        {{ $jadwal['terpakai'] }}
                                                    </span>

                                                    <!-- Sisa -->
                                                    @php
                                                        $sisa = $jadwal['sisa'];
                                                        $warnaSisa =
                                                            $sisa == 0
                                                                ? 'bg-danger'
                                                                : ($sisa <= 3
                                                                    ? 'bg-warning text-dark'
                                                                    : 'bg-success');
                                                        $ikonSisa =
                                                            $sisa == 0
                                                                ? 'bi-x-circle-fill'
                                                                : ($sisa <= 3
                                                                    ? 'bi-exclamation-circle-fill'
                                                                    : 'bi-check-circle-fill');
                                                        $tooltipSisa = $sisa == 0 ? 'Kuota penuh' : 'Masih tersedia';
                                                    @endphp
                                                    <span class="badge {{ $warnaSisa }}" title="{{ $tooltipSisa }}">
                                                        <i class="bi {{ $ikonSisa }} me-1"></i> Sisa:
                                                        {{ $sisa }}
                                                    </span>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



            <!--JADWAL DOKTER HARI INI-->
            <div class="table_dashboard_responsive mb-1 d-none">
                <div class="card border-0 shadow-sm overflow-hidden mb-3 rounded-2 p-3">
                    <h5 class="judulcard fw-bold text-dark mb-3">
                        <i class="bi bi-calendar-week"></i>
                        Jadwal Dokter Hari Ini ({{ ucfirst(now()->locale('id')->translatedFormat('l')) }})
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm table-hover align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Dokter</th>
                                    <th>Spesialis</th>
                                    <th>Ruang</th>
                                    <th>Sesi</th>
                                    <th>Jam Praktik</th>
                                    <th>Kuota</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $grouped = collect($jadwalDokterHariIni)->groupBy('nama_dokter');
                                    $no = 1;
                                @endphp

                                @foreach ($grouped as $namaDokter => $jadwalList)
                                    @foreach ($jadwalList as $i => $jadwal)
                                        <tr>
                                            @if ($i === 0)
                                                <td class="text-center align-middle fw-semibold"
                                                    rowspan="{{ $jadwalList->count() }}">{{ $no++ }}</td>
                                                <td class="text-center align-middle fw-semibold"
                                                    rowspan="{{ $jadwalList->count() }}">
                                                    {{ $namaDokter }}
                                                </td>
                                                <td class="text-center align-middle fw-semibold"
                                                    rowspan="{{ $jadwalList->count() }}">
                                                    {{ $jadwal['spesialis'] }}
                                                </td>
                                            @endif
                                            <td class="text-center">
                                                <span class="badge bg-secondary">{{ $jadwal['ruang'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $daftarSesi = explode(',', $jadwal['sesi']);
                                                    $warnaSesi = [
                                                        'Pagi' => 'bg-success',
                                                        'Siang' => 'bg-warning text-dark',
                                                        'Sore' => 'bg-primary',
                                                    ];
                                                @endphp
                                                @foreach ($daftarSesi as $sesi)
                                                    <span
                                                        class="badge {{ $warnaSesi[trim($sesi)] ?? 'bg-secondary' }} me-1">
                                                        {{ trim($sesi) }}
                                                    </span>
                                                @endforeach
                                            </td>
                                            <td class="text-center align-middle fw-semibold">
                                                {{ $jadwal['jam_mulai'] }} - {{ $jadwal['jam_selesai'] }}
                                            </td>
                                            <td class="text-center">
                                                <!-- Total Kuota -->
                                                <span class="badge bg-light text-dark border border-secondary mb-1"
                                                    title="Kuota maksimal per sesi">
                                                    <i class="bi bi-person-lines-fill me-1"></i> Total:
                                                    {{ $jadwal['kuota'] }}
                                                </span><br>

                                                <!-- Terpakai -->
                                                <span class="badge bg-warning text-dark mb-1"
                                                    title="Jumlah pasien yang sudah mendaftar">
                                                    <i class="bi bi-person-check-fill me-1"></i> Terpakai:
                                                    {{ $jadwal['terpakai'] }}
                                                </span><br>

                                                <!-- Sisa -->
                                                @php
                                                    $sisa = $jadwal['sisa'];
                                                    $warnaSisa =
                                                        $sisa == 0
                                                            ? 'bg-danger'
                                                            : ($sisa <= 3
                                                                ? 'bg-warning text-dark'
                                                                : 'bg-success');
                                                    $ikonSisa =
                                                        $sisa == 0
                                                            ? 'bi-x-circle-fill'
                                                            : ($sisa <= 3
                                                                ? 'bi-exclamation-circle-fill'
                                                                : 'bi-check-circle-fill');
                                                    $tooltipSisa = $sisa == 0 ? 'Kuota penuh' : 'Masih tersedia';
                                                @endphp
                                                <span class="badge {{ $warnaSisa }}" title="{{ $tooltipSisa }}">
                                                    <i class="bi {{ $ikonSisa }} me-1"></i> Sisa: {{ $sisa }}
                                                </span>
                                            </td>

                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- === KUNJUNGAN HARI INI (VERSI BIRU) === -->
            <div class="card mb-1">
                <div class="shadow border-1 rounded-4 h-100">
                    <!-- Header Biru Full -->
                    <div class="rounded-top-4 px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2"
                        style="background-color: #0d6efd;">
                        <h5 class="fw-bold text-white mb-0">
                            <i class="fas fa-calendar-check me-2"></i> Kunjungan Hari Ini
                        </h5>

                        <div class="d-flex flex-column flex-md-row gap-2">
                            <!-- Dropdown Sesi -->
                            <select class="form-select form-select-sm sesiSelectKunjungan"
                                onchange="filterTabelKunjungan(this)">
                                <option disabled selected>Pilih Sesi</option>
                                @foreach ($kunjunganPerSesi as $sesi => $list)
                                    <option value="{{ Str::slug($sesi) }}">{{ $sesi }}</option>
                                @endforeach
                            </select>

                            <!-- Dropdown Dokter -->
                            <select class="form-select form-select-sm dokterSelect" onchange="filterTabelKunjungan(this)">
                                <option value="semua" selected>Semua Dokter</option>
                                @php
                                    $listDokterUnik = collect($kunjunganPerSesi)
                                        ->flatten()
                                        ->pluck('dokter')
                                        ->filter()
                                        ->unique('Id_Dokter');
                                @endphp
                                @foreach ($listDokterUnik as $dokter)
                                    <option value="{{ Str::slug($dokter->Nama_Dokter) }}">{{ $dokter->Nama_Dokter }}
                                    </option>
                                @endforeach
                            </select>

                            <!-- Tombol Refresh -->
                            <form action="{{ route('admin.updateStatusMenunggu') }}" method="GET" class="d-inline">
                                <button type="submit" class="btn btn-sm btn-light text-primary border">
                                    <i class="bi bi-arrow-clockwise me-1"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Loop per sesi -->
                    @foreach ($kunjunganPerSesi as $namaSesi => $kunjunganList)
                        <div class="card mb-4 py-2 border-0 shadow-sm sesi-kunjungan-table kunjungan-sesi-wrapper"
                            id="sesi_Kunjungan{{ Str::slug($namaSesi) }}" data-sesi="{{ Str::slug($namaSesi) }}"
                            style="display: none;">

                            <div class="card-body bg-light rounded-bottom-4">
                                <div class="card-header bg-primary-subtle text-dark">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="bi bi-window-stack me-1"></i> Sesi {{ $namaSesi }}
                                    </h6>
                                </div>
                                @if ($kunjunganList->isEmpty())
                                    <div class="text-center text-muted py-3">
                                        <i class="fas fa-info-circle fa-lg mb-2"></i><br>
                                        <small>Tidak ada kunjungan pada sesi
                                            <strong>{{ strtolower($namaSesi) }}</strong>.</small>
                                    </div>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-sm table-hover table-bordered align-middle mb-0">
                                            <thead class="table-light text-center">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Pasien</th>
                                                    <th>Nomor Urut</th>
                                                    <th>Layanan</th>
                                                    <th>Dokter</th>
                                                    <th>Ruangan</th>
                                                    <th>Jadwal</th>
                                                    <th>Tombol</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($kunjunganList as $i => $kunjungan)
                                                    <tr class="baris-kunjungan"
                                                        data-dokter="{{ Str::slug($kunjungan->dokter->Nama_Dokter ?? 'kosong') }}">
                                                        <td class="text-center align-middle fw-semibold">
                                                            {{ $i + 1 }}</td>
                                                        <td class="text-center align-middle fw-semibold">
                                                            {{ $kunjungan->Nama_Pasien ?? '-' }}</td>
                                                        <td class="text-center align-middle fw-semibold">
                                                            {{ $kunjungan->Nomor_Urut ?? '-' }}</td>
                                                        <td class="text-center align-middle fw-semibold">
                                                            {{ $kunjungan->layanan->Nama_Layanan ?? '-' }}</td>
                                                        <td class="text-center align-middle fw-semibold">
                                                            {{ $kunjungan->dokter->Nama_Dokter ?? '-' }}</td>
                                                        <td class="text-center align-middle fw-semibold">
                                                            {{ $kunjungan->ruangan->Nama_Ruangan ?? '-' }}</td>
                                                        <td class="text-center align-middle fw-semibold">
                                                            {{ $kunjungan->Jadwal ?? '-' }}</td>
                                                        <td class="text-center">
                                                            @php
                                                                $dokterId = $kunjungan->Id_Dokter;
                                                                $jadwal = $kunjungan->Jadwal;
                                                                $adaYangDiperiksa = $allKunjunganHariIni
                                                                    ->where('Status', 'diperiksa')
                                                                    ->where('Id_Dokter', $dokterId)
                                                                    ->where('Jadwal', $jadwal)
                                                                    ->where(
                                                                        'Id_Kunjungan',
                                                                        '!=',
                                                                        $kunjungan->Id_Kunjungan,
                                                                    )
                                                                    ->isNotEmpty();
                                                            @endphp

                                                            <form method="POST"
                                                                action="{{ route('admin.kunjungan.ubahStatus', $kunjungan->Id_Kunjungan) }}"
                                                                class="d-inline">
                                                                @csrf
                                                                <input type="hidden" name="status" value="diperiksa">
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-info mb-2"
                                                                    {{ $adaYangDiperiksa ? 'disabled' : '' }}>
                                                                    <i class="bi bi-hearts"> Periksa</i>
                                                                </button>
                                                            </form>

                                                            <form
                                                                action="{{ route('admin.kunjungan.belumHadir', $kunjungan->Id_Kunjungan) }}"
                                                                method="POST" class="d-inline">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger mb-2">
                                                                    <i class="bi bi-x-circle"> Belum Hadir</i>
                                                                </button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- === PASIEN DIPERIKSA DAN Belum Hadir PER SESI (2 kolom) === -->
            <div class="row g-3 mb-2">
                <!-- === Kartu Pasien Diperiksa === -->
                <div class="col-lg-6">
                    <div class="card shadow border-0 rounded-4 overflow-hidden">
                        <!-- Header -->
                        <div class="bg-info bg-gradient p-3">
                            <h5 class="fw-bold text-white mb-0">
                                <i class="bi bi-person-hearts me-2 fs-5 align-middle"></i>
                                Pasien Diperiksa
                            </h5>
                        </div>

                        <!-- Filter Dropdown -->
                        <div class="bg-light p-3 border-bottom border-info-subtle">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                                <!-- Sesi -->
                                <select id="sesiSelectDiperiksa"
                                    class="form-select form-select-sm border-info text-primary bg-white"
                                    onchange="showSesiTableDiperiksa(this.value)">
                                    <option disabled selected>Pilih Sesi</option>
                                    @foreach ($kunjunganDiperiksaPerSesi as $sesi => $listPasien)
                                        <option value="sesiDiperiksa-{{ $sesi }}">{{ $sesi }}</option>
                                    @endforeach
                                </select>

                                <!-- Dokter -->
                                @php
                                    $dokterUnikDiperiksa = collect($kunjunganDiperiksaPerSesi)
                                        ->flatten()
                                        ->pluck('dokter')
                                        ->filter()
                                        ->unique('Id_Dokter');
                                @endphp
                                <select id="dokterSelectDiperiksa"
                                    class="form-select form-select-sm border-info text-primary bg-white"
                                    onchange="filterDokterDiperiksa(this.value)">
                                    <option value="semua" selected>Semua Dokter</option>
                                    @foreach ($dokterUnikDiperiksa as $dokter)
                                        <option value="{{ Str::slug($dokter->Nama_Dokter) }}">{{ $dokter->Nama_Dokter }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Konten Sesi -->
                        <div class="card-body bg-white">
                            @foreach ($kunjunganDiperiksaPerSesi as $sesi => $listPasien)
                                @php
                                    $warnaSesi = 'bg-primary-subtle text-primary';
                                @endphp

                                <div class="sesiDiperiksa-table" id="sesiDiperiksa-{{ $sesi }}"
                                    style="display: none;">
                                    <div class="card border border-info-subtle mb-3">
                                        <div class="card-header py-2 {{ $warnaSesi }}">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="bi bi-hearts me-1"></i> Sesi {{ $sesi }}
                                            </h6>
                                        </div>
                                        <div class="card-body bg-light rounded-bottom">
                                            @if ($listPasien->isEmpty())
                                                <div class="text-center text-muted py-3">
                                                    <i class="bi bi-calendar2-heart fs-4"></i><br>
                                                    <small>Tidak ada pasien diperiksa di sesi
                                                        <strong>{{ strtolower($sesi) }}</strong>.</small>
                                                </div>
                                            @else
                                                <div class="table-responsive">
                                                    <table
                                                        class="table table-sm table-bordered table-hover align-middle mb-0">
                                                        <thead class="table-light text-center">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Pasien</th>
                                                                <th>Nomor Urut</th>
                                                                <th>Layanan</th>
                                                                <th>Dokter</th>
                                                                <th>Ruangan</th>
                                                                <th>Jadwal</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($listPasien as $index => $kunjungan)
                                                                @php
                                                                    $slugDokter = Str::slug(
                                                                        $kunjungan->dokter->Nama_Dokter ??
                                                                            'tanpa-dokter',
                                                                    );
                                                                @endphp
                                                                <tr data-dokter="{{ $slugDokter }}">
                                                                    <td class="text-center fw-semibold">
                                                                        {{ $index + 1 }}</td>
                                                                    <td class="text-center fw-semibold">
                                                                        {{ $kunjungan->Nama_Pasien }}</td>
                                                                    <td class="text-center fw-semibold">
                                                                        {{ $kunjungan->Nomor_Urut }}</td>
                                                                    <td class="text-center fw-semibold">
                                                                        {{ $kunjungan->Id_Layanan ?? '-' }}</td>
                                                                    <td class="text-center fw-semibold">
                                                                        {{ $kunjungan->dokter->Nama_Dokter ?? '-' }}</td>
                                                                    <td class="text-center fw-semibold">
                                                                        {{ $kunjungan->ruangan->Nama_Ruangan ?? '-' }}
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="badge rounded-pill bg-primary-subtle text-primary">
                                                                            {{ $kunjungan->Jadwal }}
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
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- === Kartu Pasien Belum Hadir === -->
                <div class="col-lg-6">
                    <div class="card shadow border-0 rounded-4 overflow-hidden">
                        <!-- Header -->
                        <div class="bg-danger bg-gradient p-3">
                            <h5 class="fw-bold text-white mb-0">
                                <i class="bi bi-person-fill-x me-2 fs-5 align-middle"></i> Pasien Belum Hadir
                            </h5>
                        </div>
                        <!-- Filter Dropdown -->
                        <div class="bg-light p-3 border-bottom border-danger-subtle">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-2">
                                <!-- Sesi -->
                                <select id="sesiSelectTidakSelesai"
                                    class="form-select form-select-sm border-danger text-danger bg-white"
                                    onchange="showSesiTableTidakSelesai(this.value)">
                                    <option disabled selected>Pilih Sesi</option>
                                    @foreach ($pasienTidakSelesaiPerSesi as $sesi => $listPasien)
                                        <option value="sesiTidakSelesai-{{ $sesi }}">{{ $sesi }}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Dokter -->
                                @php
                                    $dokterUnikBelumHadir = collect($pasienTidakSelesaiPerSesi)
                                        ->flatten()
                                        ->pluck('dokter')
                                        ->filter()
                                        ->unique('Id_Dokter');
                                @endphp
                                <select id="dokterSelectTidakSelesai"
                                    class="form-select form-select-sm border-danger text-danger bg-white"
                                    onchange="filterDokterTidakSelesai(this.value)">
                                    <option value="semua" selected>Semua Dokter</option>
                                    @foreach ($dokterUnikBelumHadir as $dokter)
                                        <option value="{{ Str::slug($dokter->Nama_Dokter) }}">
                                            {{ $dokter->Nama_Dokter }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Konten Sesi -->
                        <div class="card-body bg-white">
                            @foreach ($pasienTidakSelesaiPerSesi as $sesi => $listPasien)
                                <div class="sesiTidakSelesai-table" id="sesiTidakSelesai-{{ $sesi }}"
                                    style="display: none;">
                                    <div class="card border border-danger-subtle mb-3">
                                        <div class="card-header bg-danger-subtle text-danger py-2">
                                            <h6 class="mb-0 fw-semibold">
                                                <i class="bi bi-x-circle me-1"></i> Sesi {{ $sesi }}
                                            </h6>
                                        </div>
                                        <div class="card-body bg-light rounded-bottom">
                                            @if ($listPasien->isEmpty())
                                                <div class="text-center text-muted py-3">
                                                    <i class="bi bi-calendar2-x fs-4"></i><br>
                                                    <small>Tidak ada pasien belum hadir di sesi
                                                        <strong>{{ strtolower($sesi) }}</strong>.</small>
                                                </div>
                                            @else
                                                <div class="table-responsive">
                                                    <table
                                                        class="table table-sm table-bordered table-hover align-middle mb-0">
                                                        <thead class="table-light text-center">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Pasien</th>
                                                                <th>No. Urut</th>
                                                                <th>Layanan</th>
                                                                <th>Dokter</th>
                                                                <th>Ruangan</th>
                                                                <th>Jadwal</th>
                                                                <th>Tombol</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($listPasien as $index => $kunjungan)
                                                                @php
                                                                    $slugDokter = Str::slug(
                                                                        $kunjungan->dokter->Nama_Dokter ??
                                                                            'tanpa-dokter',
                                                                    );
                                                                @endphp
                                                                <tr data-dokter="{{ $slugDokter }}">
                                                                    <td class="text-center fw-semibold">
                                                                        {{ $index + 1 }}</td>
                                                                    <td class="text-center fw-semibold">
                                                                        {{ $kunjungan->Nama_Pasien }}</td>
                                                                    <td class="text-center fw-semibold">
                                                                        {{ $kunjungan->Nomor_Urut }}</td>
                                                                    <td class="text-center fw-semibold">
                                                                        {{ $kunjungan->Id_Layanan ?? '-' }}</td>
                                                                    <td class="text-center fw-semibold">
                                                                        {{ $kunjungan->dokter->Nama_Dokter ?? '-' }}</td>
                                                                    <td class="text-center fw-semibold">
                                                                        {{ $kunjungan->ruangan->Nama_Ruangan ?? '-' }}
                                                                    </td>
                                                                    <td>
                                                                        <span class="badge bg-danger-subtle text-danger">
                                                                            {{ $kunjungan->Jadwal }}
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @php
                                                                            $dokterId = $kunjungan->Id_Dokter;
                                                                            $jadwal = $kunjungan->Jadwal;
                                                                            $adaYangDiperiksa = $allKunjunganHariIni
                                                                                ->where('Status', 'diperiksa')
                                                                                ->where('Id_Dokter', $dokterId)
                                                                                ->where('Jadwal', $jadwal)
                                                                                ->where(
                                                                                    'Id_Kunjungan',
                                                                                    '!=',
                                                                                    $kunjungan->Id_Kunjungan,
                                                                                )
                                                                                ->isNotEmpty();
                                                                        @endphp
                                                                        <form method="POST"
                                                                            action="{{ route('admin.kunjungan.ubahStatus', $kunjungan->Id_Kunjungan) }}"
                                                                            class="d-inline">
                                                                            @csrf
                                                                            <input type="hidden" name="status"
                                                                                value="menunggu">
                                                                            <button type="submit"
                                                                                class="btn btn-sm btn-outline-danger"
                                                                                {{ $adaYangDiperiksa ? 'disabled' : '' }}>
                                                                                <i
                                                                                    class="bi bi-arrow-clockwise me-1"></i>Panggil
                                                                                Kembali
                                                                            </button>
                                                                        </form>
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
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <!-- === Kartu Pasien Selesai === -->
            <div class="px-2 px-md-6 mb-2">
                <div class="card shadow border-1 rounded-4 h-100">
                    <!-- Header Hijau Full -->
                    <div class="rounded-top-4 px-4 py-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2"
                        style="background-color: #198754;">
                        <h5 class="fw-bold text-white mb-0">
                            <i class="bi bi-person-check-fill me-2"></i> Pasien Selesai Hari Ini
                        </h5>

                        <div class="d-flex flex-column flex-md-row gap-2">
                            <!-- Dropdown Sesi -->
                            <select id="sesiSelectSelesai" class="form-select form-select-sm"
                                onchange="showSesiTableSelesai(this.value)">
                                <option disabled selected>Pilih Sesi</option>
                                @foreach ($pasienSelesaiPerSesi as $sesi => $listPasien)
                                    <option value="sesiSelesai-{{ $sesi }}">{{ $sesi }}</option>
                                @endforeach
                            </select>

                            <!-- Dropdown Dokter -->
                            @php
                                $dokterUnikSelesai = collect($pasienSelesaiPerSesi)
                                    ->flatten()
                                    ->pluck('dokter')
                                    ->filter()
                                    ->unique('Id_Dokter');
                            @endphp
                            <select id="dokterSelectSelesai" class="form-select form-select-sm"
                                onchange="filterDokterSelesai(this.value)">
                                <option value="semua" selected>Semua Dokter</option>
                                @foreach ($dokterUnikSelesai as $dokter)
                                    <option value="{{ Str::slug($dokter->Nama_Dokter) }}">{{ $dokter->Nama_Dokter }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tabel berdasarkan sesi -->
                    <div class="card-body p-4 bg-white">
                        @foreach ($pasienSelesaiPerSesi as $sesi => $listPasien)
                            @php $warnaSesi = 'bg-success-subtle text-dark'; @endphp

                            <div class="card sesiSelesai-table mb-4" id="sesiSelesai-{{ $sesi }}"
                                style="display: none;">
                                <div class="card-header py-2 {{ $warnaSesi }}">
                                    <h6 class="mb-0 fw-semibold">
                                        <i class="bi bi-check2-square me-1"></i> Sesi {{ $sesi }}
                                    </h6>
                                </div>

                                <div class="card-body bg-light rounded-bottom-4">
                                    @if ($listPasien->isEmpty())
                                        <div class="text-center text-muted py-3">
                                            <i class="bi bi-calendar2-check fa-lg mb-2"></i><br>
                                            <small>Tidak ada pasien selesai di sesi
                                                <strong>{{ strtolower($sesi) }}</strong>.</small>
                                        </div>
                                    @else
                                        <div class="table-responsive">
                                            <table
                                                class="table table-sm table-bordered table-hover align-middle mb-0 dokter-table">
                                                <thead class="table-light text-center">
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Nama Pasien</th>
                                                        <th>NIK</th>
                                                        <th>No Urut</th>
                                                        <th>Layanan</th>
                                                        <th>Dokter</th>
                                                        <th>Ruangan</th>
                                                        <th>Jadwal</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($listPasien as $index => $kunjungan)
                                                        @php
                                                            $slugDokter = Str::slug(
                                                                $kunjungan->dokter->Nama_Dokter ?? 'tanpa-dokter',
                                                            );
                                                        @endphp
                                                        <tr data-dokter="{{ $slugDokter }}">
                                                            <td class="text-center align-middle fw-semibold">
                                                                {{ $index + 1 }}</td>
                                                            <td class="text-center align-middle fw-semibold">
                                                                {{ $kunjungan->Nama_Pasien }}</td>
                                                            <td class="text-center align-middle fw-semibold">
                                                                {{ $kunjungan->Nik }}</td>
                                                            <td class="text-center align-middle fw-semibold">
                                                                {{ $kunjungan->Nomor_Urut }}</td>
                                                            <td class="text-center align-middle fw-semibold">
                                                                {{ $kunjungan->Id_Layanan ?? '-' }}</td>
                                                            <td class="text-center align-middle fw-semibold">
                                                                {{ $kunjungan->dokter->Nama_Dokter ?? '-' }}</td>
                                                            <td class="text-center align-middle fw-semibold">
                                                                {{ $kunjungan->ruangan->Nama_Ruangan ?? '-' }}</td>
                                                            <td>
                                                                <span
                                                                    class="badge bg-success-subtle text-dark">{{ $kunjungan->Jadwal }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!--GRAFIK KUNJUNGAN DAN PASIEN -->
            <div class="px-2 px-md-3 mb-2">
                <div class="card shadow mb-4 border-1 rounded-4 p-4 h-100">
                    <div class="card-header bg-gradient-primary text-dark">
                        <h5 class="mb-0 text-black-50"><span style="position: relative; display: inline-block;">
                                <i class="bi bi-bar-chart-line"
                                    style="color: red; position: absolute; left: 1px; top: 0;"></i>
                                <i class="bi bi-bar-chart-line"
                                    style="color: orange; position: absolute; left: 2px; top: 0;"></i>
                                <i class="bi bi-bar-chart-line" style="color: blue;"></i>
                            </span>
                            Grafik Kunjungan & Pasien (30 Hari Terakhir)
                        </h5>
                    </div>
                    <div class="card-body" style="height: 400px;"> <!-- ← Set tinggi di sini -->
                        <canvas id="kunjunganPasienChart" style="width: 100%; height: 100%;"></canvas>
                    </div>

                </div>
            </div>
        </div>
        <!-- ============================== /KANAN ============================== -->
    </div>
    <!--========================================MODAL===========================================-->
    <!-- MODAL KUNJUNGAN HARI INI -->
    <div class="modal fade" id="modalKunjunganHariIni" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Kunjungan Hari Ini</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Pasien</th>
                                <th>Nomor Urut</th>
                                <th>Layanan</th>
                                <th>Dokter</th>
                                <th>Ruangan</th>
                                <th>Jadwal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kunjunganHariIniDetail as $kunjungan)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $kunjungan->Nama_Pasien }}</td>
                                    <td>{{ $kunjungan->Nomor_Urut }}</td>
                                    <td>{{ $kunjungan->layanan->Nama_Layanan ?? '-' }}</td>
                                    <td>{{ $kunjungan->dokter->Nama_Dokter ?? '-' }}</td>
                                    <td>{{ $kunjungan->ruangan->Nama_Ruangan ?? '-' }}</td>
                                    <td>{{ $kunjungan->Jadwal }}</td>
                                    <td>{{ $kunjungan->Status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Tidak ada kunjungan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $kunjunganHariIniDetail->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL PASIEN HARI INI -->
    <div class="modal fade" id="modalPasienHariIni" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Pasien Baru Hari Ini</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-sm">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>No Telepon</th>
                                <th>Jenis Kelamin</th>
                                <th>Umur</th>
                                <th>Alamat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pasienHariIni as $pasien)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $pasien->Nama_Pasien }}</td>
                                    <td>{{ $pasien->No_Tlp }}</td>
                                    <td>{{ $pasien->Jk }}</td>
                                    <td>{{ $pasien->Umur }}</td>
                                    <td>{{ $pasien->Alamat }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">Tidak ada pasien baru</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL Layanan HARI INI -->
    <div class="modal fade" id="modalLayananHariIni" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">Layanan Terbanyak Hari Ini</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group">
                        @forelse($layananHariIni as $layanan)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $layanan->Nama_Layanan }}
                                <span class="badge bg-primary rounded-pill">{{ $layanan->jumlah }}</span>
                            </li>
                        @empty
                            <li class="list-group-item text-muted">Belum ada data</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- SCRIPT JSON LAYANAN -->
    <script>
        window.layananHariLabels = @json($layananHariLabels);
        window.layananHariData = @json($layananHariData);
        window.layananHariDetail = @json($layananHariDetail);
        window.layananBulanLabels = @json($layananBulanLabels);
        window.layananBulanData = @json($layananBulanData);
        window.layananBulanDetail = @json($layananBulanDetail);

        window.datagrafikumur = @json($datagrafikumur);
        window.datagrafikjeniskelamin = @json($datagrafikjeniskelamin);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('JKChart').getContext('2d');
            const chartData = @json($grafikGender);

            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Jumlah Pasien',
                        data: chartData.data,
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.8)', // Laki-laki
                            'rgba(255, 99, 132, 0.8)' // Perempuan
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Chart JS – Pie Chart'
                        }
                    }
                }
            });
        });
    </script>
@endsection
