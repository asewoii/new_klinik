    <form method="POST" action="{{ route('indikasi.select_delete') }}" id="select-delete-form">
        @csrf
        @method('DELETE')

        <!-- Header & Tombol Aksi -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
            <span id="liveTime" class="badge bg-info-subtle text-dark fs-6">--:--</span>

            <div class="mt-2 mt-md-0">
                <a href="{{ route('indikasi.create') }}" class="btn btn-sm btn-primary me-2">
                    <i class="bi bi-plus-circle me-1"></i> {{ __('messages.Tambah_Keluhan') }}
                </a>
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data yang dipilih?')">
                    <i class="bi bi-trash me-1"></i> {{ __('messages.Hapus_Data') }}
                </button>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-ul me-2"></i>
                    {{ __('messages.Daftar_Layanan') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table" class="table table-bordered table-hover table-striped align-middle text-center">
                        <thead class="table-primary">
                            <tr class="text-start">
                                <th><input type="checkbox" id="select-all"></th>
                                <th style="width: 100px;">{{ __('messages.Opsi') }}</th>
                                <th style="width: 50px;">{{ __('messages.No') }}</th>
                                <th><span data-translate="Kode Layanan">Kode Layanan</span></th>
                                <th style="min-width: 120px;"><span data-translate="Jenis Layanan">Jenis Layanan</span></th>
                                <th style="min-width: 100px;">{{ __('messages.Dibuat_Oleh') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr class="text-start">
                                    <td><input type="checkbox" name="selected_indikasi[]" value="{{ $item->Kode_Indikasi }}"></td>
                                    <td>
                                        <a href="{{ route('indikasi.edit', $item->Kode_Indikasi) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <button type="submit" class="btn btn-sm btn-danger w-auto">
                                            <i class="bi bi-trash"></i> {{ __('messages.Hapus') }}
                                        </button>
                                    </td>
                                    <td>{{ ($data->currentPage() - 1) * $data->perPage() + $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $item->Kode_Indikasi }}</td>
                                    <td>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modalDeskripsi{{ $item->Kode_Indikasi }}">
                                            @if (Lang::has('messages.' . $item->deskripsi))
                                                {{ __('messages.' . $item->deskripsi) }}
                                            @else
                                                <span data-translate="{{ $item->deskripsi }}">{{ $item->deskripsi }}</span>
                                            @endif
                                        </a>
                                    </td>
                                    <td>{{ $item->Create_By }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">Tidak ada data ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Statistik & Pagination -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mt-3">
            <span class="stats-text text-muted mb-2 mb-md-0">
                <strong>Total Keluhan:</strong> {{ $data->total() }} |
                <strong>Ditampilkan:</strong> {{ $data->count() }} |
                <strong>Halaman:</strong> {{ $data->currentPage() }} / {{ $data->lastPage() }} |
                <strong>Baris:</strong> {{ $data->firstItem() }} - {{ $data->lastItem() }}
            </span>
            <div>
                @if ($data->lastPage() > 1 || $data->count())
                    {{ $data->withQueryString()->onEachSide(1)->links() }}
                @else
                    <nav>
                        <ul class="pagination mb-0">
                            <li class="page-item active">
                                <span class="page-link">1</span>
                            </li>
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </form>