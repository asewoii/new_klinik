<!DOCTYPE html>
<html>
<head>
    <title>Laporan Ruangan</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
    </style>
</head>
<body>
    <h3>Laporan Data Ruangan</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Ruangan</th>
                <th>Jenis Ruangan</th>
                <th>Lantai</th>
                <th>Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ruangans as $i => $r)
                <tr>
                    <td>{{ ($data->ruangans() - 1) * $ruangans->perPage() + $index + 1 }}</td>
                    <td>{{ $r->Nama_Ruangan }}</td>
                    <td>{{ $r->Jenis_Ruangan }}</td>
                    <td>{{ $r->Lantai }}</td>
                    <td>{{ $r->Status }}</td>
                    <td>{{ $r->Keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
