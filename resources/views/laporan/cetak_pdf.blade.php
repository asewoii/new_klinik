<h3 style="text-align: center;">Laporan Data Pasien Klinik</h3>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th>No</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Tanggal Lahir</th>
            <th>Jenis Kelamin</th>
            <th>No Telepon</th>
            <th>Tanggal Registrasi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $item)
        <tr>
            <td>{{ $item + 1 }}</td>
            <td>{{ $item->Nik }}</td>
            <td>{{ $item->Nama_Pasien }}</td>
            <td>{{ $item->Tanggal_Lahir }}</td>
            <td>{{ $item->Jk }}</td>
            <td>{{ $item->No_Tlp }}</td>
            <td>{{ $item->Tanggal_Registrasi }}</td>
        </tr>
        @endforeach
    </tbody>
</table>