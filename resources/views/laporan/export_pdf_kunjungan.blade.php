<!DOCTYPE html>
<html>
<head>
    <title>Laporan Kunjungan</title>
    <style>
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h3>Laporan Kunjungan</h3>
    <table>
        <thead>
            <tr> 
                <th>No</th>
                <th>ID</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Layanan</th>
                <th>Dokter</th>
                <th>Ruangan</th>
                <th>Jadwal</th>
                <th>Tanggal</th>
                <th>Keluhan</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $d)
                <tr>
                    <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                    <td>{{ $d->Id_Kunjungan }}</td>
                    <td>{{ $d->Nik }}</td>
                    <td>{{ $d->Nama_Pasien }}</td>
                    <td>{{ $d->layanan->Nama_Layanan ?? '-'}}
                    <td>{{ $d->dokter->Nama_Dokter ?? '-'  }}</td>
                    <td>{{ $d->Id_Ruangan }}</td>
                    <td>{{ $d->Jadwal}}</td>
                    <td>{{ \Carbon\Carbon::parse($d->Tanggal_Registrasi)->format('d-m-Y') }}</td>
                    <td>{{ $d->Keluhan}}</td>
                    <td>{{ $d->Status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
