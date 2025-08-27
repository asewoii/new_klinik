@php
    use Carbon\Carbon;

    $startDate = request('start_date');
    $endDate = request('end_date');

    if ($startDate && $endDate && $startDate == $endDate) {
        $judulTanggal = 'TANGGAL ' . Carbon::parse($startDate)->translatedFormat('d-m-Y');
    } elseif ($startDate || $endDate) {
        $startFormatted = $startDate ? Carbon::parse($startDate)->translatedFormat('d-m-Y') : '-';
        $endFormatted = $endDate ? Carbon::parse($endDate)->translatedFormat('d-m-Y') : '-';
        $judulTanggal = 'TANGGAL ' . $startFormatted . ' - ' . $endFormatted;
    } else {
        $judulTanggal = 'SEMUA TANGGAL';
    }

    // Ambil nama dokter dan sesi
    $namaDokter = 'Semua Dokter';
    $namaSesi = 'Semua Sesi';
    $statusKunjungan = 'Semua Status';

    if(request('dokter_id') && isset($dokters)) {
        $dokter = $dokters->where('Id_Dokter', request('dokter_id'))->first();
        if ($dokter) {
            $namaDokter = $dokter->Nama_Dokter;
        }
    }

    if(request('sesi_id') && isset($semuaSesi)) {
        $sesi = $semuaSesi->where('Id_Sesi', request('sesi_id'))->first();
        if ($sesi) {
            $namaSesi = ucfirst($sesi->Nama_Sesi);
        }
    }

    if(request('status')) {
        $statusMap = [
            'menunggu' => 'Menunggu',
            'diperiksa' => 'Sedang Diperiksa',
            'selesai' => 'Selesai',
            'tidak selesai' => 'Tidak Selesai'
        ];
        $statusKunjungan = $statusMap[strtolower(request('status'))] ?? request('status');
    }
@endphp

<h3 style="text-align: center; margin-bottom: 4px;">
    LAPORAN KUNJUNGAN {{ $judulTanggal }}
</h3>
<p style="text-align: center; font-size: 13px; margin-bottom: 15px;">
    Dokter: <strong>{{ $namaDokter }}</strong> |
    Sesi: <strong>{{ $namaSesi }}</strong> |
    Status: <strong>{{ ucfirst($statusKunjungan) }}</strong>
</p>

<table class="table table-dark">
    <thead>
       <tr class="table-active">
            <th scope="row">#</th>
            <th scope="row">Nama Pasien</th>
            <th scope="row">NIK</th>
            <th scope="row">No Telepon</th>
            <th scope="row">Keluhan</th>
            <th scope="row">Dokter</th>
            <th scope="row">Layanan</th>
            <th scope="row">Jam</th>
            <th scope="row">Ruangan</th>
            <th scope="row">Jadwal Kedatangan</th>
            <th scope="row">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kunjungan as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->Nama_Pasien }}</td>
            <td>{{ $item->Nik }}</td>
            <td>{{ $item->pasien->No_Tlp ??'-' }}</td>
            <td>{{ $item->Keluhan }}</td>
            <td>{{ $item->dokter->Nama_Dokter ?? '-' }}</td>
            <td>{{ $item->layanan->Nama_Layanan ?? '-' }}</td>
            <td>{{ $item->Jadwal }}</td>
            <td>{{ $item->ruangan->Nama_Ruangan ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($item->Jadwal_Kedatangan)->format('d-m-Y') }}</td>
            <td>{{ $item->Status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
