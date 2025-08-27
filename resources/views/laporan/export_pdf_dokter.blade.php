<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Dokter</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 13px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        table, th, td {
            border: 1px solid #000;
            font-size: 13px;
            padding: 5px;
        }

        th {
            background-color: #f2f2f2;
            text-align: left;
        }

        .jadwal-item {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <h3 style="text-align:center;">Laporan Data Dokter</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Dokter</th>
                <th>Spesialis</th>
                <th>No. Telp</th>
                <th>Jadwal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dokters as $i => $d)
                <tr>
                    <td>{{ ($data->dokters() - 1) * $dokters->perPage() + $index + 1 }}</td>
                    <td>{{ $d->Nama_Dokter }}</td>
                    <td>{{ $d->Spesialis }}</td>
                    <td>{{ $d->No_Telp }}</td>
                    <td>
                        @php
                            $jadwal = json_decode($d->Jadwal_Dokter, true);
                        @endphp

                        @if ($jadwal)
                            @foreach ($jadwal as $hari => $slots)
                                <strong>{{ $hari }}</strong><br>
                                @foreach ($slots as $slot)
                                    • {{ $slot['start'] }} - {{ $slot['end'] }},
                                    Ruang: {{ $slot['ruang'] }},
                                    Sesi: {{ $slot['sesi'] }}<br>
                                @endforeach
                            @endforeach
                        @else
                            Tidak Ada Jadwal
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
