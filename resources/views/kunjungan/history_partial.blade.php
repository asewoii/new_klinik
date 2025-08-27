@if($riwayat->isEmpty())
    <div class="alert alert-info">
        Tidak ada riwayat kunjungan.
    </div>
@else
    <div class="table-responsive">
        <table class="table table-striped table-sm">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Jadwal Kedatangan</th>
                    <th>Nomor Urut</th>
                    <th>Keluhan</th>
                    <th>Dokter</th>
                    <th>Layanan</th>
                    <th>Jam</th>
                    <th>Ruangan</th>      
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($riwayat as $i => $item)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->Jadwal_Kedatangan)->translatedFormat('d/m/Y ') }}</td>
                        <td>{{ $item->Nomor_Urut }}</td>
                        <td>{{ $item->Keluhan }}</td>
                        <td>{{ $item->dokter->Nama_Dokter ?? '-' }}</td>
                        <td>{{ $item->layanan->Nama_Layanan ?? '-' }}</td>
                        <td>{{ $item->Jadwal }}</td>
                        <td>{{ $item->ruangan->Nama_Ruangan ??'-' }}</td>
                        <td>
                                {{ $item->Status }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
