@extends('layouts.app')
@push('css')
<link rel="stylesheet" href="{{ asset('template/back') }}/dist/libs/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">
<link rel="stylesheet" href="{{ asset('template/back') }}/dist/libs/ckeditor/samples/css/samples.css">
@endpush

@section('content')
<div class="container-fluid">
    <div class="card bg-light-info shadow-none position-relative overflow-hidden" style="border: solid 0.5px #ccc;">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">{{ $title }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="/">Beranda</a></li>
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="text-muted text-decoration-none" href="{{ route('travel_routes.index') }}">Halaman Travel Route</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">{{ $subtitle }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3 text-center mb-n5">
                    <img src="{{ asset('template/back') }}/dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                </div>
            </div>
        </div>
    </div>

    <section class="datatables">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Ada beberapa masalah dengan data yang anda masukkan.
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('travel_routes.store') }}"  enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="form-group mb-3">
                                    <label for="name">Nama Rute</label>
                                    <input type="text" name="name" class="form-control" id="name" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="start">Rute Awal</label>
                                    <input type="text" name="start" class="form-control" id="start" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="end">Rute Akhir</label>
                                    <input type="text" name="end" class="form-control" id="end" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="price">Harga <span class="text-danger">*</span></label>
                                    <input type="text" name="price" class="form-control" id="price" value="{{ old('price', 0) }}" placeholder="Masukkan Harga Sewa" oninput="formatPrice(this)" required>
                                </div>
                                <script>
                                    // Format Harga
                                    function formatPrice(input) {
                                        let value = input.value.replace(/[^0-9.]/g, '');
                                        let parts = value.split('.');
                                        if (parts.length > 2) parts = [parts[0], parts[1]];
                                        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                        input.value = parts.join('.');
                                    }
                                </script>
                                <div class="form-group mb-3">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control" name="description" id="description">{{ old('description') }}</textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="position">Urutan</label>
                                    <input type="number" name="position" class="form-control" id="position">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="image">Gambar</label>
                                    <input type="file" name="image" class="form-control" id="image" onchange="previewImage()">
                                    <canvas id="preview_canvas" style="display: none; max-width: 100%; margin-top: 10px;"></canvas>
                                    <img id="preview_image" src="#" alt="Preview Logo" style="display: none; max-width: 100%; margin-top: 10px;">

                                    <script>
                                        function previewImage() {
                                            var previewCanvas = document.getElementById('preview_canvas');
                                            var previewImage = document.getElementById('preview_image');
                                            var fileInput = document.getElementById('image');
                                            var file = fileInput.files[0];
                                            var reader = new FileReader();

                                            reader.onload = function(e) {
                                                var img = new Image();
                                                img.src = e.target.result;

                                                img.onload = function() {
                                                    var canvasContext = previewCanvas.getContext('2d');
                                                    var maxWidth = 300; // Max width diperbesar
                                                    var scaleFactor = maxWidth / img.width;
                                                    var newHeight = img.height * scaleFactor;

                                                    // Atur dimensi canvas
                                                    previewCanvas.width = maxWidth;
                                                    previewCanvas.height = newHeight;

                                                    // Gambar ke canvas
                                                    canvasContext.drawImage(img, 0, 0, maxWidth, newHeight);

                                                    // Tampilkan pratinjau
                                                    previewCanvas.style.display = 'block';
                                                    previewImage.style.display = 'none';
                                                };
                                            };

                                            if (file) {
                                                reader.readAsDataURL(file); // Membaca file sebagai URL data
                                            } else {
                                                // Reset pratinjau jika tidak ada file
                                                previewImage.src = '';
                                                previewCanvas.style.display = 'none';
                                            }
                                        }
                                    </script>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary btn-sm mb-3"><i class="fa fa-save"></i> Simpan</button>
                                    <a class="btn btn-warning btn-sm mb-3" href="{{ route('travel_routes.index') }}"><i class="fa fa-undo"></i> Kembali</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('script')



<script src="{{ asset('template/back') }}/dist/libs/ckeditor/ckeditor.js"></script>
<script src="{{ asset('template/back') }}/dist/libs/ckeditor/samples/js/sample.js"></script>
<script>
    //default
    initSample();
</script>
<script data-sample="1">
    CKEDITOR.replace("description", {
        height: 150,
    });
</script>


@endpush