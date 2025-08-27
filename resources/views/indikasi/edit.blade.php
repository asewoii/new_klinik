<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Layanan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(4px);
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary-subtle text-primary">
                    <h5 class="mb-0">✏️ Edit Layanan</h5>
                </div>
                <div class="card-body">

                    <!-- Form Edit -->
                    <form action="{{ route('indikasi.update', $data->Id_Layanan) }}" method="POST">
                        @csrf
                        @method('PUT')


                        <div class="mb-3">
                            <label for="Id_Layanan" class="form-label">Kode Keluhan</label>
                            <input type="text" id="editKode" name="Id_Layanan" class="form-control" value="{{ $data->Id_Layanan }}" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="Nama_Layanan" class="form-label">Deskripsi</label>
                            <textarea name="Nama_Layanan" class="form-control" rows="4" required>{{ $data->Nama_Layanan }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('indikasi.index') }}" class="btn btn-outline-secondary">← Kembali</a>
                            <button type="submit" class="btn btn-primary">💾 Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
