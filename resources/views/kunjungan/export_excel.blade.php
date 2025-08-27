<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Pasien</th>
            <th>NIK</th>
            <th>No Telepon</th>
            <th>Keluhan</th>
            <th>Dokter</th>
            <th>Layanan</th>
            <th>Jam</th>
            <th>Ruangan</th>
            <th>Jadwal Kedatangan</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kunjungan as $i => $item)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $item->Nama_Pasien }}</td>
            <td>{{ $item->Nik }}</td>
            <td>{{ $item->pasien->No_Tlp ??'-' }}</td>
            <td>{{ $item->Keluhan }}</td>
            <td>{{ $item->dokter->Nama_Dokter ?? '-' }}</td>
            <td>{{ $item->layanan->Nama_Layanan ?? '-' }}</td>
            <td>{{ $item->Jadwal }}</td>
            <td>{{ $item->ruangan->Nama_Ruangan ?? '-' }}</td>
            <td>{{ \Carbon\Carbon::parse($item->Jadwal_Kedatangan)->format('d-m-Y') }}</td>
            <td>{{ $item->Status }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
