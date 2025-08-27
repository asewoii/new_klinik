<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pemeriksaan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h3>Laporan Pemeriksaan Pasien</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Pasien</th>
                <th>Dokter</th>
                <th>Diagnosa</th>
                <th>Tindakan</th>
                <th>Resep</th>
                <th>Catatan</th>
                <th>Jadwal</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $p)
                <tr>
                    <td>{{ ($data->currentPage() - 1) * $data->perPage() + $index + 1 }}</td>
                    <td>{{ $p->kunjungan->Nama_Pasien ?? '-' }}</td>
                    <td>{{ $p->dokter->Nama_Dokter ?? '-' }}</td>
                    <td>{{ $p->Diagnosa }}</td>
                    <td>{{ $p->Tindakan }}</td>
                    <td>{{ $p->Resep}}</td>
                    <td>{{ $p->Catatan }}</td>
                    <td>{{ $p->Jam_Pemeriksaan}}</td>
                    <td>{{ \Carbon\Carbon::parse($p->Tanggal_Pemeriksaan)->format('d-m-Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
