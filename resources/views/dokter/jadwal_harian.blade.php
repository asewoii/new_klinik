@extends('layouts.nav_admin')
@section('title', 'Jadwal Harian Dokter')




@section('content')

<div class="container">
    <h3 class="mb-4">Jadwal Harian Dokter per Ruangan</h3>

    @php
        $hariList = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    @endphp

    @foreach ($hariList as $hari)
        <h5 class="mt-4">{{ $hari }}</h5>
        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Ruangan</th>
                        <th>Lantai</th>
                        <th>Nama Dokter</th>
                        <th>Spesialis</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Sesi</th>
                        <th>Kuota</th>
                        <th>Kuota Terpakai</th>
                        <th>Kuota Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $jadwalHariIni = collect();

                        foreach ($jadwal as $dokter) {
                            $dokterId = $dokter['Id_Dokter'];
                            $nama = $dokter['nama'];
                            $spesialis = $dokter['spesialis'];

                            if (!empty($dokter['jadwal'][$hari])) {
                                foreach ($dokter['jadwal'][$hari] as $j) {
                                    $jadwalHariIni->push([
                                        'dokter_id' => $dokterId,
                                        'ruang'     => $j['ruang'] ?? '-',
                                        'nama'      => $nama,
                                        'spesialis' => $spesialis,
                                        'start'     => $j['start'],
                                        'end'       => $j['end'],
                                        'sesi'      => $j['sesi'],
                                        'kuota'     => $j['kuota'],
                                    ]);
                                }
                            }
                        }

                        $perRuangan = $jadwalHariIni->groupBy('ruang');
                    @endphp

                    @forelse ($perRuangan as $ruangan => $items)
                        <tr>
                            <td>{{ $ruangan }}</td>
                            <td>{{ $ruangans[$ruangan] ?? '-' }}</td>
                            <td>
                                @foreach ($items as $j)
                                    • {{ $j['nama'] }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($items as $j)
                                    • {{ $j['spesialis'] }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($items as $j)
                                    • {{ $j['start'] }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($items as $j)
                                    • {{ $j['end'] }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($items as $j)
                                    • {{ $j['sesi'] }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($items as $j)
                                    • {{ $j['kuota'] }}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach ($items as $j)
                                    @php
                                        $dokterId = $j['dokter_id'];
                                        $ruanganId = $j['ruang'];
                                        $jam = $j['start'];
                                        $key = "{$dokterId}|{$ruanganId}|{$jam}";
                                        $terpakai = isset($kunjungan[$key]) ? $kunjungan[$key]->sum('jumlah') : 0;
                                    @endphp
                                    • {{ $terpakai }}<br>
                                @endforeach
                            </td>

                            <td>
                                @foreach ($items as $j)
                                    @php
                                        $dokterId = $j['dokter_id'];
                                        $ruanganId = $j['ruang'];
                                        $jam = $j['start'];
                                        $key = "{$dokterId}|{$ruanganId}|{$jam}";
                                        $terpakai = isset($kunjungan[$key]) ? $kunjungan[$key]->sum('jumlah') : 0;
                                        $sisa = max(0, $j['kuota'] - $terpakai);
                                    @endphp
                                    • <span class="{{ $sisa == 0 ? 'text-danger fw-bold' : '' }}">{{ $sisa }}</span><br>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center">Tidak ada jadwal</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach
</div>
@endsection
