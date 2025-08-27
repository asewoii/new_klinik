<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Kunjungan Pasien</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        h3 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .small-text {
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>

    <h3>Laporan Kunjungan Pasien</h3>

    <table>
        <thead>
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
                    <td>{{ ucfirst($kunjungan->Status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="small-text" style="margin-top: 30px;">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i:s') }}
    </div>

</body>
</html>
