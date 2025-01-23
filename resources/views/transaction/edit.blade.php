@extends('layouts.app')

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
                                <a class="text-muted text-decoration-none" href="{{ route('transactions.index') }}">Halaman Transaksi</a>
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

                        <form method="POST" action="{{ route('transactions.update', $data_transactions->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="form-group mb-3">
                                    <label for="date">Tanggal Transaksi</label>
                                    <span class="text-danger">*</span>
                                    <input
                                        type="date"
                                        name="date"
                                        class="form-control"
                                        id="date"
                                        value="{{ old('date', $data_transactions->date) }}"> <!-- Menggunakan data transaksi -->
                                </div>

                                <div class="form-group mb-3">
                                    <label for="transaction_category_id">Kategori Transaksi</label>
                                    <span class="text-danger">*</span>
                                    <select id="transaction_category_id" name="transaction_category_id" class="form-control" required>
                                        <option value="" disabled selected>--Pilih Kategori--</option>
                                        @foreach ($data_transaction_categories as $p)
                                        <option value="{{ $p->id }}" {{ old('transaction_category_id', $data_transactions->transaction_category_id) == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="cash_id">Kas</label>
                                    <span class="text-danger">*</span>
                                    <select id="cash_id" name="cash_id" class="form-control" required>
                                        <option value="" disabled selected>--Pilih Kas--</option>
                                        @foreach ($data_cash as $p)
                                        <option value="{{ $p->id }}" {{ old('cash_id', $data_transactions->cash_id) == $p->id ? 'selected' : '' }}>
                                            {{ $p->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="name">Nama Transaksi</label>
                                    <span class="text-danger">*</span>
                                    <input
                                        type="text"
                                        name="name"
                                        class="form-control"
                                        id="name"
                                        value="{{ old('name', $data_transactions->name) }}"
                                        required>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="amount">Jumlah</label>
                                    <span class="text-danger">*</span>
                                    <input
                                        type="text"
                                        name="amount"
                                        class="form-control"
                                        id="amount"
                                        value="{{ old('amount', $data_transactions->amount) }}"
                                        oninput="formatPrice(this)">
                                </div>

                                <script>
                                    // Fungsi untuk memformat angka
                                    function formatPrice(input) {
                                        let value = input.value.replace(/[^0-9.]/g, ''); // Hanya angka dan titik
                                        let parts = value.split('.');

                                        // Memastikan hanya ada satu titik desimal
                                        if (parts.length > 2) parts = [parts[0], parts[1]];

                                        // Memformat bagian ribuan
                                        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');

                                        // Jika ada bagian desimal, hanya tampilkan 2 digit setelah titik
                                        if (parts[1]) {
                                            parts[1] = parts[1].substring(0, 2); // Batasi 2 digit setelah titik
                                        }

                                        // Gabungkan bagian sebelum dan setelah titik
                                        input.value = parts.join('.');
                                    }

                                    // Format input amount saat halaman dimuat
                                    document.addEventListener("DOMContentLoaded", function() {
                                        var amountInput = document.getElementById("amount");
                                        if (amountInput) {
                                            formatPrice(amountInput); // Memformat nilai awal saat halaman dimuat
                                        }
                                    });
                                </script>


                                <div class="form-group mb-3">
                                    <label for="description">Deskripsi</label>
                                    <textarea
                                        class="form-control"
                                        name="description"
                                        id="description">{{ old('description', $data_transactions->description) }}</textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label for="image">Gambar/Bukti</label>
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
                                                    var maxWidth = 300;
                                                    var scaleFactor = maxWidth / img.width;
                                                    var newHeight = img.height * scaleFactor;

                                                    previewCanvas.width = maxWidth;
                                                    previewCanvas.height = newHeight;

                                                    canvasContext.drawImage(img, 0, 0, maxWidth, newHeight);

                                                    previewCanvas.style.display = 'block';
                                                    previewImage.style.display = 'none';
                                                };
                                            };

                                            if (file) {
                                                reader.readAsDataURL(file);
                                            } else {
                                                previewImage.src = '';
                                                previewCanvas.style.display = 'none';
                                            }
                                        }
                                    </script>
                                </div>

                                <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary btn-sm mb-3">
                                        <i class="fa fa-save"></i> Simpan
                                    </button>
                                    <a class="btn btn-warning btn-sm mb-3" href="{{ route('transactions.index') }}">
                                        <i class="fa fa-undo"></i> Kembali
                                    </a>
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