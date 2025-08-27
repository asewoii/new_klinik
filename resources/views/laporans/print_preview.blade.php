<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Print Preview - Laporan Kunjungan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            padding: 20px;
            background: #f7f7f7;
        }

        .btn-group {
            margin-bottom: 20px;
            text-align: right;
        }

        button {
            padding: 6px 12px;
            margin-left: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        @media print {
            .btn-group {
                display: none;
            }

            body {
                background: white;
            }
        }
    </style>
</head>
<body>

    <div class="btn-group">
        <button onclick="window.print()">🖨 Print</button>
        <a href="{{ route('laporans.download', request()->query()) }}">
            <button>⬇ Download PDF</button>
        </a>
        <button onclick="window.close()">❌ Close</button>
    </div>

    <h3 style="text-align: center;">Laporan Kunjungan Pasien</h3>

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

</body>
</html>
