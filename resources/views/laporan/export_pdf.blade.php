<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 4px; text-align: left; }
    </style>
</head>
<body>
    <h3>Laporan {{ ucfirst($jenis) }}</h3>

    @if(count($data))
        <table>
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
    @else
        <p>Tidak ada data untuk ditampilkan.</p>
    @endif
</body>
</html>