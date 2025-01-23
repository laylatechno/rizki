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
                                <a class="text-muted text-decoration-none" href="{{ route('adjustments.index') }}">Halaman Adjustment</a>
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

                        <form action="{{ route('adjustments.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group mb-3">
                                <label for="adjustment_date">Tanggal Adjustment</label>
                                <input type="date" name="adjustment_date" id="adjustment_date" class="form-control" value="{{ old('adjustment_date', now()->toDateString()) }}" required>

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

                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label for="product_id">Cari Produk </label>
                                    <select class="form-control" id="product_id" name="product_id">
                                        <option value="">--Pilih Produk--</option>
                                        @foreach ($data_products as $produkItem)
                                        <option value="{{ $produkItem->id }}"
                                            data-order-price="{{ $produkItem->cost_price }}"
                                            data-stock="{{ $produkItem->stock }}"
                                            data-barcode="{{ $produkItem->barcode }}">
                                            {{ $produkItem->name }}
                                        </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <table id="scroll_hor" class="table border table-striped table-bordered display nowrap" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th style="text-align: left;">Produk</th>
                                        <th>Harga</th>
                                        <th>Stock</th>
                                        <th width="15%">Qty</th>
                                        <th>Total</th>
                                        <th>Reason</th>
                                        <th width="5%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>

                            <!-- Tambahkan elemen untuk total harga -->
                            <div class="row mt-3 mb-3">
                                <div class="col-md-12 text-right">
                                    <h5>Total Harga: <span id="total_harga_display">Rp 0</span></h5>
                                </div>
                            </div>






                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan</button>
                            <a href="{{ route('adjustments.index') }}" class="btn btn-danger" style="color:white;"><i
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
<script src="{{ asset('template/back') }}/dist/libs/select2/dist/js/select2.full.min.js"></script>
<script src="{{ asset('template/back') }}/dist/libs/select2/dist/js/select2.min.js"></script>
<script src="{{ asset('template/back') }}/dist/js/forms/select2.init.js"></script>

<script>
    $(document).ready(function() {
        // Inisialisasi Select2
        $('#product_id').select2();

        // Event listener ketika dropdown produk berubah
        $('#product_id').on('change', function() {
            var selectedProductId = $(this).val();
            var selectedProductName = $('#product_id option:selected').text();
            var selectedProductStock = $('#product_id option:selected').data('stock');
            var selectedProductPrice = $('#product_id option:selected').data('order-price');

            console.log("Selected Product ID:", selectedProductId);
            console.log("Selected Product Name:", selectedProductName);
            console.log("Selected Product Stock:", selectedProductStock);
            console.log("Selected Product Price:", selectedProductPrice);

            if (isNaN(selectedProductStock)) {
                console.error("Stock is not valid:", selectedProductStock);
                selectedProductStock = 0; // Set nilai default jika tidak valid
            }

            // Tambahkan produk ke tabel
            updateCartRow(selectedProductId, selectedProductName, selectedProductStock, selectedProductPrice);
        });

        function updateCartRow(productId, productName, productStock, productPrice) {
            if (isNaN(productPrice)) {
                console.error("Product Price is not valid:", productPrice);
                productPrice = 0; // Set ke default jika tidak valid
            }

            var existingProductRow = $('#scroll_hor tbody tr').filter(function() {
                return $(this).find('input[name="product_id[]"]').val() == productId;
            });

            if (existingProductRow.length > 0) {
                var quantityInput = existingProductRow.find('.quantity');
                var currentQty = parseInt(quantityInput.val());
                quantityInput.val(currentQty + 1);
            } else {
                var newRow = `
                <tr>
                    <td></td>
                    <td style="text-align:left;">
                        <input type="hidden" name="product_id[]" value="${productId}">
                        <label>${productName}</label>
                    </td>
                    <td>
                        <input type="number" class="form-control cost_price" name="cost_price[]" value="${productPrice}">
                    </td>
                    <td>
                        <input type="number" class="form-control stock" name="stock[]" value="${productStock}" readonly>
                    </td>
                    <td>
                        <input type="number" class="form-control quantity" name="quantity[]" value="1">
                    </td>
                    <td>
                        <input type="text" class="form-control total" name="total[]" readonly>
                    </td>
                    <td>
                        <input type="text" class="form-control reason" name="reason[]" placeholder="Alasan adjustment">
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm btn-remove-product"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;

                $('#scroll_hor tbody').append(newRow);
            }

            updateRowNumbers();
            calculateTotal();
        }

        function updateRowNumbers() {
            $('#scroll_hor tbody tr').each(function(index, row) {
                $(row).find('td:first').text(index + 1);
            });
        }

        function calculateTotal() {
            var totalBayar = 0;
            $('#scroll_hor tbody tr').each(function() {
                var quantity = parseInt($(this).find('.quantity').val()) || 0;
                var hargaBeli = parseFloat($(this).find('.cost_price').val()) || 0;
                var total = quantity * hargaBeli;
                $(this).find('.total').val(total);
                totalBayar += total;
            });

            $('#total_harga_display').text(totalBayar);
            $('.total_harga_display').val(totalBayar < 0 ? 0 : totalBayar);
        }

        $(document).on('click', '.btn-remove-product', function() {
            $(this).closest('tr').remove();
            updateRowNumbers();
            calculateTotal();
        });

        $(document).on('input', '.quantity', function() {
            calculateTotal();
        });

        // Form submission dengan AJAX
        $('#adjustment-form').submit(function(e) {
            e.preventDefault(); // Mencegah form submit biasa

            var formData = $(this).serialize(); // Mengambil data form

            $.ajax({
                url: '{{ route('adjustments.store') }}', // Menggunakan route yang dinamakan adjustments.store
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Mengambil token CSRF dari meta tag
                },
                success: function(response) {
                    alert('Data berhasil disimpan!');
                    // Lakukan tindakan setelah sukses (misalnya reset form atau tampilkan pesan sukses)
                },
                error: function(xhr, status, error) {
                    alert('Terjadi kesalahan: ' + error);
                }
            });
        });
    });
</script>




@endpush