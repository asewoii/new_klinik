<!DOCTYPE html>
<html>
<head>
    <title>No Urut Kunjungan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f8fa;
            margin: 0;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .container {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            max-width: 95%;
            width: 100%;
        }
        h2 {
            color: #007acc;
            margin-bottom: 20px;
            text-align: center;
        }
        .patient-info-table {
            width: 50%;
            border: 1px solid #ccc;
            border-collapse: collapse;
            background: #f9f9f9;
            font-size: 14px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 6px;
            overflow: hidden;
        }
        .patient-info-table th, .patient-info-table td {
            padding: 4px 10px;
            border: 2px solid #ddd;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 10px;
        }
        th {
            background: #007acc;
            color: white;
            text-align: center;
            padding: 12px;
        }
        td {
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #eee;
        }
        tr:nth-child(even) {
            background: #f0f8ff;
        }
        tr:hover {
            background: #e6f2ff;
        }
        .no-data {
            text-align: center;
            color: #888;
            margin-top: 20px;
        }
        .qr-section {
            text-align: center;
            margin: 20px 0 30px 0;
        }
        .qr-section h4 {
            margin-bottom: 15px;
            color: #007acc;
        }
        .download-btn {
            padding: 10px 20px;
            background: #007acc;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            display: inline-block;
            margin-top: 10px;
            transition: background 0.3s;
        }
        .download-btn:hover {
            background: #005a99;
        }
        @media (max-width: 600px) {
            .container { padding: 20px; margin: 20px; }
            th, td { font-size: 12px; padding: 8px; }
            .patient-info-table { width: 100%; }
            .patient-info-table th, .patient-info-table td { font-size: 13px; }
            body { padding: 20px; }
        }
    </style>
</head>
<body>
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 20px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif

        <h2>Nomor Urut Kunjungan Hari Ini</h2>

        @php
            $dokterId = $kunjunganData->first()->Id_Dokter ?? '';
            $tanggalKunjungan = optional($kunjunganData->first())->Jadwal_Kedatangan
                ? \Carbon\Carbon::parse($kunjunganData->first()->Jadwal_Kedatangan)->format('Y-m-d')
                : \Carbon\Carbon::now()->format('Y-m-d');
        @endphp

    <div id="live-antrian"
    data-dokter="{{ $dokterId }}"
    data-tanggal="{{ $tanggalKunjungan }}"
    class="my-4 text-center fw-bold fs-5 text-primary-emphasis">
    <div class="d-inline-flex align-items-center gap-2">
        <span>🔊 Sedang Panggil No :</span>
            <span id="antrian-live">
                <span class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></span>
            </span>
        </div>
    </div>


        @if($dataPasien)
            <div style="display: flex; justify-content: flex-end; margin-bottom: 5px;">
                <a href="{{ route('pasien.datadiri', ['id' => $dataPasien->Id_Pasien]) }}" 
                    class="download-btn" style="background-color: rgb(15, 83, 120); font-size: 12px;">
                    <i class="bi bi-arrow-bar-left"></i> Kembali Ke Data Diri
                </a>
            </div>

            <table class="patient-info-table">
                <tr>
                    <th>Nama Pasien</th>
                    <td>{{ $dataPasien->Nama_Pasien }}</td>
                </tr>
                <tr>
                    <th>No. Telepon</th>
                    <td>{{ $dataPasien->No_Tlp }}</td>
                </tr>
            </table>

            <table>
                <tr>
                    <th>No</th>
                    <th>No. Urut / Total</th>
                    <th>Keluhan</th>
                    <th>Poli</th>
                    <th>Dokter</th>
                    <th>Ruangan</th>
                    <th>Jam</th>
                    <th>Tanggal Kunjungan</th>
                    <th>Status</th>
                </tr>
                @foreach($kunjunganData as $index => $row)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $row->Nomor_Urut }} / {{ $row->Total_Antrian ?? '?' }}</strong></td>
                    <td class="text-start">{{ $row->Keluhan }}</td>
                    <td>{{ $row->Nama_Layanan ?? '-' }}</td>
                    <td>{{ $row->Nama_Dokter ?? '-' }}</td>
                    <td>{{ $row->Nama_Ruangan ?? '-' }}</td>
                    <td>{{ $row->Jadwal }}</td>
                    <td>{{ \Carbon\Carbon::parse($row->Jadwal_Kedatangan)->format('d-m-Y') }}</td>
                   <td>
                        @php
                            $status = $row->Status;
                            $warna = match($status) {
                                'Selesai ' => 'success',          // hijau
                                'menunggu' => 'primary',         // biru
                                'diperiksa' => 'info',           // biru muda
                                'Belum Hadir' => 'danger',     // merah
                                default => 'secondary',
                            };
                        @endphp
                        <span class="badge bg-{{ $warna }}">{{ $status }}</span>
                    </td>
                </tr>
                @endforeach
            </table>
        @else
            <p class="no-data">Data kunjungan tidak ditemukan untuk hari ini. Mohon pastikan tanggalnya sesuai.</p>
        @endif

        <div class="qr-section">
            <h4>QR Kode Kunjungan</h4>
            <div id="qrcode">
                @if(isset($qr))
                    {!! QrCode::size(180)->generate($qr) !!}
                    <p style="font-size: 12px; color: #666; margin-top: 8px;">
                        Simpan QR ini untuk akses ulang halaman ini.
                        <a href="{{ route('pasien.kunjungan.download_qr', ['id' => $dataPasien->Id_Kunjungan ?? $kunjunganData->first()->Id_Kunjungan ?? '-' ]) }}"class="text-primary text-decoration-underline" target="_blank">
                        Download QR
                        </a>
                    </p>
                @else
                    <p>QR belum tersedia.</p>
                @endif
            </div>
        </div>
    </div>

<script>
    function updateLiveAntrian() {
        const el = document.getElementById('live-antrian');
        const dokter = el.getAttribute('data-dokter');
        const tanggal = el.getAttribute('data-tanggal');

        if (!dokter || !tanggal) {
            console.warn("Dokter atau tanggal kosong");
            return;
        }

        fetch(`/live-antrian-dokter?dokter=${dokter}&tanggal=${tanggal}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('antrian-live').textContent = data.nomor ?? '-';
            })
            .catch(err => {
                document.getElementById('antrian-live').textContent = '⚠️';
                console.error('Gagal ambil data antrian:', err);
            });
    }

    // Jalankan saat load dan tiap 30 detik
    updateLiveAntrian();
    setInterval(updateLiveAntrian, 30000);
</script>

</body>
</html>
