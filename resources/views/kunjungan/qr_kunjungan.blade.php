<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QR Kunjungan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container text-center mt-5">
    <h3 class="mb-4">Kunjungan Berhasil!</h3>

    @if(session('qr_data'))
        <div class="bg-white p-4 rounded shadow d-inline-block">
            <h6 class="mb-3">Scan QR Kunjungan</h6>

            {{-- Tampilkan QR --}}
            {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate(session('qr_data')) !!}

            {{-- Tombol Download --}}
            @php
                $qrImage = base64_encode(
                    \SimpleSoftwareIO\QrCode\Facades\QrCode::format('png')->size(300)->generate(session('qr_data'))
                );
            @endphp

            <div class="mt-3">
                <a href="data:image/png;base64,{{ $qrImage }}"
                   download="qr_kunjungan.png"
                   class="btn btn-success">
                    Download QR
                </a>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            Data QR tidak tersedia.
        </div>
    @endif

</div>

</body>
</html>
