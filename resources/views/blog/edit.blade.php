@extends('layouts.app')
@push('css')
<link rel="stylesheet" href="{{ asset('template/back') }}/dist/libs/ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css">
<link rel="stylesheet" href="{{ asset('template/back') }}/dist/libs/ckeditor/samples/css/samples.css">
<link rel="stylesheet" href="{{ asset('template/back') }}/dist/libs/select2/dist/css/select2.min.css">

<style>
    .select2-container--default .select2-selection--single .select2-selection__arrow b {
        border-color: #888 transparent transparent transparent;
        border-style: solid;
        border-width: 5px 4px 0 4px;
        height: 0;
        left: 50%;
        margin-left: -4px;
        margin-top: 20px;
        position: absolute;
        top: 50%;
        width: 0;
    }
</style>
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
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{ route('customers.index') }}">Halaman Blog</a></li>
                            <li class="breadcrumb-item">{{ $subtitle }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('template/back') }}/dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="datatables">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> Ada beberapa masalah dengan data yang anda masukkan.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <form method="POST" action="{{ route('blogs.update', $data_blogs->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')





                                <div class="row">
                                    <div class="form-group mb-3">
                                        <label for="title">Judul Berita <span class="text-danger">*</span></label>
                                        <input type="text" name="title" class="form-control" id="title" value="{{ $data_blogs->title }}" required>
                                        <input type="hidden" name="slug" class="form-control" id="slug" value="{{ $data_blogs->slug }}" required readonly>
                                    </div>

                                    <script>
                                        // Ambil elemen input untuk name dan slug
                                        const nameInput = document.getElementById('title');
                                        const slugInput = document.getElementById('slug');

                                        // Fungsi untuk membuat slug
                                        function generateSlug(value) {
                                            return value
                                                .toLowerCase() // Ubah menjadi huruf kecil
                                                .trim() // Hapus spasi di awal dan akhir
                                                .replace(/[\s\W-]+/g, '-') // Ganti spasi atau karakter non-alphanumeric dengan "-"
                                                .replace(/^-+|-+$/g, ''); // Hapus dash di awal atau akhir
                                        }

                                        // Event listener untuk mengisi slug saat name diinput
                                        nameInput.addEventListener('input', function() {
                                            slugInput.value = generateSlug(nameInput.value);
                                        });
                                    </script>

                                    <div class="form-group mb-3">
                                        <label for="category">Kategori <span class="text-danger">*</span></label>
                                        <select
                                            class="select2 form-control"
                                            style="width: 100%; height: 36px" name="blog_category_id"
                                            id="blog_category_id">
                                            <option value="">--Pilih Kategori--</option>
                                            @foreach ($data_blog_categories as $p)
                                            <option value="{{ $p->id }}" {{ $p->id == $data_blogs->blog_category_id ? 'selected' : '' }}>
                                                {{ $p->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>





                                    <div class="form-group mb-3">
                                        <label for="posting_date">Tanggal Posting <span class="text-danger">*</span></label>
                                        <input type="date" name="posting_date" class="form-control" id="posting_date" value="{{ $data_blogs->posting_date }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="writer">Penulis <span class="text-danger">*</span></label>
                                        <input type="text" name="writer" class="form-control" id="writer" value="{{ $data_blogs->writer }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="resume">Rangkuman </label>
                                        <input type="text" name="resume" class="form-control" id="resume" value="{{ $data_blogs->resume }}">
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="description">Deskripsi <span class="text-danger">*</span></label>
                                        <textarea cols="80" id="description" name="description" rows="10" data-sample="1" data-sample-short>{{ $data_blogs->description }}</textarea>

                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="reference">Sumber </label>
                                        <input type="text" name="reference" class="form-control" id="reference" value="{{ $data_blogs->reference }}">
                                    </div>


                                    <div class="form-group mb-3">
                                        <label for="status">Status <span class="text-danger">*</span></label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="">--Pilih Status--</option>
                                            <option value="Aktif" {{ $data_blogs->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="Non Aktif" {{ $data_blogs->status == 'Non Aktif' ? 'selected' : '' }}>Non Aktif</option>
                                        </select>
                                    </div>



                                    <div class="form-group mb-3">
                                        <label for="position">Urutan</label>
                                        <input type="number" name="position" class="form-control" id="position" value="{{ $data_blogs->position }}">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="image">Gambar <span class="text-danger">*</span></label>
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
                                        <a class="btn btn-warning btn-sm mb-3" href="{{ route('blogs.index') }}"><i class="fa fa-undo"></i> Kembali</a>
                                    </div>
                                </div>





                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('script')

<!-- ---------------------------------------------- -->
<script src="{{ asset('template/back') }}/dist/libs/select2/dist/js/select2.full.min.js"></script>
<script src="{{ asset('template/back') }}/dist/libs/select2/dist/js/select2.min.js"></script>
<script src="{{ asset('template/back') }}/dist/js/forms/select2.init.js"></script>


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