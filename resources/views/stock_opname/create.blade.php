@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('template/back') }}/dist/libs/select2/dist/css/select2.min.css">
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
                                <a class="text-muted text-decoration-none" href="{{ route('stock_opname.index') }}">Halaman Stock Opname</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">{{ $subtitle }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3 text-center mb-n5">
                    <img src="{{ asset('template/back/dist/images/breadcrumb/ChatBc.png') }}" alt="" class="img-fluid mb-n4">
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
                            <strong>Whoops!</strong> Ada beberapa masalah dengan data yang Anda masukkan.
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form action="{{ route('stock_opname.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="opname_date">Tanggal Stock Opname</label>
                                <input type="date" name="opname_date" id="opname_date" class="form-control" value="{{ old('opname_date', now()->toDateString()) }}" required>

                            </div>

                            <div class="form-group mb-3">
                                <label for="description">Deskripsi</label>
                                <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>

                            </div>
                            <div class="form-group mb-3" id="image_container">
                                <label for="image">Gambar:</label>
                                <input type="file" class="form-control" id="image" name="image" onchange="previewImage()">
                                <canvas id="preview_canvas" style="display: none; max-width: 30%; margin-top: 10px;"></canvas>
                                <img id="preview_image" src="#" alt="Preview Logo" style="display: none; max-width: 30%; margin-top: 10px;">

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
                                                var maxWidth = 200; // Max width diperbesar
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

                            <h3 class="mb-3 mt-3">Pilih Produk</h3>
                            <table id="scroll_hor" class="table border table-striped table-bordered display nowrap" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>Stock Sistem</th>
                                        <th>
                                            Stock Real
                                            <button type="button" class="btn btn-secondary btn-sm ml-2" onclick="fillAllPhysicalStocks()">Isi Semua</button>
                                        </th>
                                        <th>Selisih</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_products as $product)
                                    <tr>
                                        <input type="hidden" name="products[{{ $product->id }}][product_id]" value="{{ $product->id }}">


                                        <td>{{ $product->name }}</td>
                                        <td>
                                            <input type="number" name="products[{{ $product->id }}][system_stock]" class="form-control" value="{{ $product->stock }}" readonly>
                                        </td>
                                        <td>
                                            <input type="number" name="products[{{ $product->id }}][physical_stock]" class="form-control" value="{{ old("products.{$product->id}.physical_stock", 0) }}" onchange="updateDifference({{ $product->id }})">
                                        </td>
                                        <td>
                                            <input type="number" name="products[{{ $product->id }}][difference]" class="form-control" value="{{ old("products.{$product->id}.difference", 0) }}" readonly>
                                        </td>
                                        <td>
                                            <input type="text" name="products[{{ $product->id }}][description_detail]" class="form-control" value="{{ old("products.{$product->id}.description_detail") }}">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>




                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i>  Simpan</button>
                            <a href="{{ route('stock_opname.index') }}" class="btn btn-danger" style="color:white;"><i
                                    class="fas fa-step-backward"></i> Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('script')
<script>
    // Fungsi untuk mengisi semua kolom 'Stock Real' dengan nilai dari 'Stock Sistem'
    function fillAllPhysicalStocks() {
        // Ambil semua input Stock Real (physical_stock) dan Stock Sistem (system_stock)
        const systemStocks = document.querySelectorAll("input[name*='[system_stock]']");
        const physicalStockInputs = document.querySelectorAll("input[name*='[physical_stock]']");

        // Loop melalui setiap baris dan isi 'Stock Real' dengan 'Stock Sistem'
        systemStocks.forEach((systemStockInput, index) => {
            let physicalStockInput = physicalStockInputs[index];
            if (physicalStockInput) {
                // Set nilai 'Stock Real' dengan nilai 'Stock Sistem'
                physicalStockInput.value = systemStockInput.value;

                // Perbarui nilai 'difference' setelah mengisi 'Stock Real'
                updateDifference(physicalStockInput);
            }
        });
    }

    // Fungsi untuk menghitung dan memperbarui selisih
    function updateDifference(physicalStockInput) {
        const row = physicalStockInput.closest('tr');
        const systemStock = parseInt(row.querySelector('[name*="[system_stock]"]').value) || 0;
        const physicalStock = parseInt(physicalStockInput.value) || 0;
        const differenceField = row.querySelector('[name*="[difference]"]');
        differenceField.value = physicalStock - systemStock;
    }

    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.querySelector('tbody'); // Pastikan tbody sudah terpilih

        // Fungsi untuk menghitung selisih ketika ada perubahan pada physical_stock
        tbody.addEventListener('input', (event) => {
            if (event.target.name.includes('physical_stock')) {
                const row = event.target.closest('tr');
                const systemStock = parseInt(row.querySelector('[name*="[system_stock]"]').value) || 0;
                const physicalStock = parseInt(event.target.value) || 0;
                const differenceField = row.querySelector('[name*="[difference]"]');
                differenceField.value = physicalStock - systemStock;
            }
        });
    });
</script>

@endpush