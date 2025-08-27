<!DOCTYPE html> 
<html lang="id"> 
<head>
    <meta charset="UTF-8"> 
    <title>Tambah Keluhan</title> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"> 
</head>

<body class="bg-light"> 
<div class="container py-5"> 
    <div class="row justify-content-center"> 
        <div class="col-md-8"> 
            <div class="card shadow-sm border-0"> 
                <div class="card-header bg-primary text-white"> 
                    <h5 class="mb-0">🦽 Tambah Keluhan</h5> 
                </div>

                <div class="card-body"> 
                    @if(session('success')) 
                        <div class="alert alert-success alert-dismissible fade show" role="alert"> 
                            {{ session('success') }} 
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> 
                        </div>
                    @endif

                    @if ($errors->any()) 
                        <div class="alert alert-danger"> 
                            <ul class="mb-0"> 
                                @foreach ($errors->all() as $error) 
                                    <li>{{ $error }}</li> 
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('indikasi.store') }}" method="POST"> 
                        @csrf

                        <div id="keluhan-list">
                            <div class="mb-3 row keluhan-item">
                                <div class="col-10">
                                    <textarea name="deskripsi[]" class="form-control" rows="2" placeholder="Tuliskan deskripsi keluhan" required></textarea>
                                </div>
                                <div class="col-2 d-flex align-items-center">
                                    <button type="button" class="btn btn-danger btn-sm remove-item d-none">✖</button>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-success btn-sm" id="add-item">+ Tambah Keluhan</button>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('indikasi.index') }}" class="btn btn-secondary">← Kembali</a>
                            <button type="submit" class="btn btn-primary">💾 Simpan Semua</button>
                        </div>
                    </form>

                </div> 
            </div> 
        </div> 
    </div> 
</div> 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script> 

<script>
document.getElementById('add-item').addEventListener('click', function () {
    const list = document.getElementById('keluhan-list');
    const item = list.querySelector('.keluhan-item');
    const clone = item.cloneNode(true);

    // Kosongkan nilai textarea di clone
    clone.querySelector('textarea').value = '';
    clone.querySelector('.remove-item').classList.remove('d-none');

    list.appendChild(clone);
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-item')) {
        e.target.closest('.keluhan-item').remove();
    }
});
</script>

</body>
</html>
