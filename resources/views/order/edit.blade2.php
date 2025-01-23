@extends('layouts.app')
@push('css')
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





    .product-list {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .product-item {
        width: 150px;
        text-align: center;
        cursor: pointer;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 5px;
        transition: transform 0.2s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        /* Menjaga gambar dan teks tetap terpisah */
    }

    .product-item:hover {
        transform: scale(1.05);
        border-color: #aaa;
    }

    .product-item img {
        width: 100%;
        height: 100px;
        /* Tentukan tinggi gambar agar konsisten */
        object-fit: cover;
        /* Menjaga aspek rasio gambar */
    }

    .product-item p {
        font-size: 14px;
        margin-top: 10px;
        /* Jarak antara gambar dan nama */
        text-overflow: ellipsis;
        /* Memotong nama jika terlalu panjang */
        white-space: nowrap;
        /* Membatasi nama agar tidak terputus */
        overflow: hidden;
        /* Menyembunyikan teks yang melebihi kontainer */
        padding: 0 5px;
        /* Memberikan sedikit padding agar teks tidak menempel ke tepi */
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
                            <li class="breadcrumb-item" aria-current="page">
                                <a class="text-muted text-decoration-none" href="{{ route('orders.index') }}">Halaman Penjualan</a>
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
                        <form id="form-edit-order" enctype="multipart/form-data">
                            @csrf
                            @method('PUT') {{-- Laravel Method Spoofing untuk method PUT --}}

                            <h5 class="card-title mb-0"><b style="color: blue;">Kode Penjualan : <input type="hidden"
                                        id="no_order" name="no_order"
                                        value="{{ $order->no_order }}">{{ $order->no_order }}</input></b></h5>


                            <br>
                            <hr>
                            <div class="form-group mb-3 col-md-6">
                                <label for="status" class="mb-2"> Status Pembayaran : </label><br>
                                <label style="margin-right: 6px;">
                                    <input type="radio" name="status" value="Pesanan Penjualan" {{ old('status', $order->status) == 'Pesanan Penjualan' ? 'checked' : '' }}> Pesanan Penjualan
                                </label>
                                <label style="margin-right: 6px;">
                                    <input type="radio" name="status" value="Lunas" {{ old('status', $order->status) == 'Lunas' ? 'checked' : '' }}> Lunas
                                </label>
                                <label style="margin-right: 6px;">
                                    <input type="radio" name="status" value="Belum Lunas" {{ old('status', $order->status) == 'Belum Lunas' ? 'checked' : '' }}> Belum Lunas
                                </label>
                                <label style="margin-right: 6px;">
                                    <input type="radio" name="status" value="Pending" {{ old('status', $order->status) == 'Pending' ? 'checked' : '' }}> Pending
                                </label>
                            </div>


                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="hari">Tanggal Penjualan</label>
                                        <span class="text-danger">*</span>
                                        <input type="date" class="form-control" id="order_date" name="order_date" value="{{ old('order_date', $order->order_date ?? date('Y-m-d')) }}">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="customer_id">Pelanggan</label>
                                        <select class="form-control" id="customer_id" name="customer_id">
                                            <option value="">--Pilih Pelanggan--</option>
                                            @foreach ($data_customers as $customerItem)
                                            <option value="{{ $customerItem->id }}"
                                                {{ old('customer_id', $order->customer_id) == $customerItem->id ? 'selected' : '' }}>
                                                {{ $customerItem->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="name" id="name" value="{{ $order->customer_id }}">
                                    </div>
                                </div>








                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="product_id">Cari Produk </label>
                                        <select class="form-control" id="product_id" name="product_id">
                                            <option value="">--Pilih Produk--</option>
                                            @foreach ($data_products as $produkItem)
                                            <option value="{{ $produkItem->id }}"
                                                data-order-price="{{ $produkItem->cost_price }}"
                                                data-stock="{{ $produkItem->stock }}"> <!-- Menambahkan data stok -->
                                                {{ $produkItem->name }}
                                            </option>

                                            @endforeach

                                        </select>
                                    </div>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="">Kas Pembayaran </label>
                                        <select name="cash_id" id="cash_id" class="form-control">
                                            <option value="">--Pilih Cash--</option>
                                            @foreach($data_cashes as $cash)
                                            <option value="{{ $cash->id }}" data-amount="{{ $cash->amount }} ">
                                                {{ $cash->name }} - Rp{{ number_format($cash->amount, 0, ',', '.') }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="product-list">
                                    @foreach ($data_products as $produkItem)
                                    <div class="product-item" data-id="{{ $produkItem->id }}"
                                        data-name="{{ $produkItem->name }}"
                                        data-stock="{{ $produkItem->stock }}"
                                        data-price="{{ $produkItem->cost_price }}">
                                        <img src="/upload/products/{{ $produkItem->image }}" alt="{{ $produkItem->name }}" />
                                        <p>{{ $produkItem->name }}</p> <!-- Nama produk di sini -->
                                    </div>
                                    @endforeach
                                </div>







                            </div>


                            <table id="scroll_hor"
                                class="table border table-striped table-bordered display nowrap"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th style="text-align: left;">Produk</th>
                                        <th>Harga</th>
                                        <th>Stock</th>
                                        <th width="15%">Qty</th>
                                        <th>Total</th>
                                        <th width="5%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderItems as $index => $item)
                                    <tr data-id="{{ $item->product_id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                                            {{ $item->product->name }}
                                        </td>
                                        <td>
                                            <input type="text" class="form-control order_price" name="order_price[]" value="{{ $item->order_price }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control stock" name="stock[]" value="{{ $item->stock }}">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control quantity" name="quantity[]" value="{{ $item->quantity }}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control total" name="total[]" value="{{ $item->order_price * $item->quantity }}" readonly>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-danger btn-sm btn-remove-product"><i class="fas fa-trash"></i></button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>






                            <!-- Total Bayar dan Input Bayar -->
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3">
                                    <h5 style="color: red; font-size:30px;" class="badge badge-danger"><b>Total
                                            Bayar: </b> <span id="total_cost">0</span></h5>
                                    <input type="hidden" name="total_cost" id="" class="form-control total_cost">
                                    <hr>
                                    <h1 style="color: red" id="info_payment"></h1>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="input_payment">Bayar:</label>
                                        <input type="text" class="form-control" id="input_payment" name="input_payment">
                                    </div>
                                    <div class="form-group">
                                        <label for="return_payment">Kembalian:</label>
                                        <input type="text" class="form-control" id="return_payment" name="return_payment" readonly>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="type_payment">Jenis Pembayaran:</label>
                                        <select name="type_payment" id="type_payment" class="form-control">
                                            <option value="">--Pilih Jenis Pembayaran--</option>
                                            <option value="CASH">CASH</option>
                                            <option value="TRANSFER">TRANSFER</option>
                                        </select>
                                    </div>
                                    <div class="form-group mb-3" id="image_container">
                                        <label for="image">Gambar:</label>
                                        <input type="file" class="form-control" id="image" name="image" onchange="previewImage()">
                                        <canvas id="preview_canvas" style="display: none; max-width: 80%; margin-top: 10px;"></canvas>
                                        <img id="preview_image" src="#" alt="Preview Logo" style="display: none; max-width: 80%; margin-top: 10px;">

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

                                    <div class="form-group mb-3">
                                        <label for="description">Keterangan:</label>
                                        <textarea name="description" id="description" class="form-control" cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="border-top">
                                <div class="card-body">
                                    <button type="submit" class="btn btn-success" style="color:white;" id="btn-save-order"><i
                                            class="fas fa-save"></i> Simpan</button>
                                    <a href="{{ route('orders.index') }}" class="btn btn-danger" style="color:white;"><i
                                            class="fas fa-step-backward"></i> Kembali</a>
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
<script src="{{ asset('template/back') }}/dist/libs/select2/dist/js/select2.full.min.js"></script>
<script src="{{ asset('template/back') }}/dist/libs/select2/dist/js/select2.min.js"></script>
<script src="{{ asset('template/back') }}/dist/js/forms/select2.init.js"></script>


<!-- Pindahkan Script di Sini -->
<script>
    document.getElementById('customer_id').addEventListener('change', function() {
        // Ambil nilai dari select box
        const selectedCustomerId = this.value;

        // Update nilai input dengan id "name"
        document.getElementById('name').value = selectedCustomerId;
    });
</script>
<script>
    $(document).ready(function() {

        $('#customer_id').on('change', function() {
            $('#name').val($(this).val());
        });

        const totalCostElement = document.getElementById('total_cost');
        const inputPaymentElement = $('#input_payment');

        // Fungsi untuk menyinkronkan input_payment dengan total_cost
        function syncPaymentWithTotalCost() {
            const totalCost = parseInt($(totalCostElement).text().replace(/[^0-9]/g, '')) || 0;
            inputPaymentElement.val(totalCost); // Atur nilai input_payment sama dengan total_cost
        }

        // Jalankan sinkronisasi saat halaman dimuat
        syncPaymentWithTotalCost();

        // Buat observer untuk memonitor perubahan pada total_cost
        const observer = new MutationObserver(syncPaymentWithTotalCost);

        // Konfigurasi observer
        observer.observe(totalCostElement, {
            characterData: true,
            subtree: true,
            childList: true
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('#customer_id').select2();
        $('#product_id').select2();
        $('#cash_id').select2();
    });
</script>


<script>
    document.getElementById('customer_id').addEventListener('change', function() {
        // Ambil nilai dari select box
        const selectedCustomerId = this.value;

        // Update nilai input dengan id "name"
        document.getElementById('name').value = selectedCustomerId;
    });
</script>
<script>
    $(document).ready(function() {


        function formatRupiah(angka) {
            var number_string = angka.toString().replace(/[^,\d]/g, ''),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // Tambahkan pemisah ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            // Tambahkan koma dan dua digit desimal jika ada
            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }



        $('.product-item').on('click', function() {
            var selectedProductId = $(this).data('id');
            var selectedProductName = $(this).data('name');
            var selectedProductStock = $(this).data('stock');
            var selectedProductPrice = $(this).data('price'); // Harga default produk

            var customerCategoryId = $('#name').val();

            if (customerCategoryId) {
                $.ajax({
                    url: '/get-product-price',
                    method: 'GET',
                    data: {
                        product_id: selectedProductId,
                        customer_category_id: customerCategoryId
                    },
                    success: function(response) {
                        if (response.price) {
                            selectedProductPrice = response.price;
                        } else {
                            console.log("No price found, using default");
                        }
                        updateCartRow(selectedProductId, selectedProductName, selectedProductStock, selectedProductPrice);
                    },
                    error: function() {
                        console.log("Error fetching price from server, using default");
                        updateCartRow(selectedProductId, selectedProductName, selectedProductStock, selectedProductPrice);
                    }
                });
            } else {
                updateCartRow(selectedProductId, selectedProductName, selectedProductStock, selectedProductPrice);
            }
        });

        $('#product_id').on('change', function() {
            var selectedProductId = $(this).val();
            var selectedProductName = $('#product_id option:selected').text();
            var selectedProductStock = $('#product_id option:selected').data('stock');
            var selectedProductPrice = $('#product_id option:selected').data('order-price');

            var customerCategoryId = $('#name').val();

            if (customerCategoryId) {
                $.ajax({
                    url: '/get-product-price',
                    method: 'GET',
                    data: {
                        product_id: selectedProductId,
                        customer_category_id: customerCategoryId
                    },
                    success: function(response) {
                        console.log("Response:", response); // Cek seluruh respons
                        if (response && response.price) {
                            selectedProductPrice = response.price;
                        } else {
                            console.log("Price is missing in response");
                        }
                        updateCartRow(selectedProductId, selectedProductName, selectedProductStock, selectedProductPrice);
                    },
                    error: function() {
                        console.log("Error fetching price from server");
                    }
                });


            } else {
                updateCartRow(selectedProductId, selectedProductName, selectedProductStock, selectedProductPrice);
            }
        });

        function updateCartRow(productId, productName, productStock, productPrice) {
            // Cek nilai productPrice sebelum digunakan
            console.log("Product ID:", productId, "Product Price:", productPrice);

            // Pastikan productPrice adalah angka sebelum diformat
            if (isNaN(productPrice)) {
                console.error("Product Price is not a valid number:", productPrice);
                productPrice = 0; // Jika tidak valid, set ke 0
            }

            var existingProductRow = $('#scroll_hor tbody tr').filter(function() {
                return $(this).find('input[name="product_id[]"]').val() == productId;
            });

            if (existingProductRow.length > 0) {
                var quantityInput = existingProductRow.find('.quantity');
                var currentQty = parseInt(quantityInput.val());
                if (currentQty < productStock) {
                    quantityInput.val(currentQty + 1);
                } else {
                    alert("Stok produk sudah habis!");
                }
            } else {
                var newRow = '<tr>' +
                    '<td></td>' +
                    '<td style="text-align:left;">' +
                    '<input type="hidden" name="product_id[]" value="' + productId + '">' +
                    '<label>' + productName + '</label></td>' +
                    '<td><input type="text" class="form-control cost_price" name="cost_price[]" value="' +
                    formatRupiah(productPrice) + '"></td>' +
                    '<td><input type="text" class="form-control stock" name="stock[]" value="' +
                    productStock + '" readonly></td>' +
                    '<td><input type="number" class="form-control quantity" name="quantity[]" value="1" max="' + productStock + '"></td>' +
                    '<td><input type="text" class="form-control total" name="total[]" readonly></td>' +
                    '<td><button type="button" class="btn btn-danger btn-sm btn-remove-product"><i class="fas fa-trash"></i></button></td>' +
                    '</tr>';

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
                var hargaBeli = parseInt($(this).find('.cost_price').val().replace(/\./g, '').replace(/[^0-9]/g, '')) || 0;
                var total = quantity * hargaBeli;
                $(this).find('.total').val(formatRupiah(total));
                totalBayar += total;
            });
            $('#total_cost').text(formatRupiah(totalBayar));
            $('.total_cost').val(formatRupiah(totalBayar));
        }

        $(document).on('input', '.quantity', function() {
            calculateTotal();
        });

        $(document).on('input', '.cost_price', function() {
            $(this).val(formatRupiah($(this).val().replace(/\./g, '')));
            calculateTotal();
        });

        $(document).on('click', '.btn-remove-product', function() {
            $(this).closest('tr').remove();
            updateRowNumbers();
        });

        $('#input_payment').on('input', function() {
            var inputBayar = $(this).val().replace(/[^\d]/g, '');
            $(this).val(formatRupiah(inputBayar));
            hitungKembalian();
            tampilkanInfoPembayaranKurang();
        });

        function tampilkanInfoPembayaranKurang() {
            var totalBayar = parseInt($('#total_cost').text().replace(/[^0-9]/g, ''));
            var inputBayar = parseInt($('#input_payment').val().replace(/\./g, '')) || 0;
            if (inputBayar < totalBayar) {
                $('#info_payment').text('Bayar masih kurang');
            } else {
                $('#info_payment').text('');
            }
        }

        function hitungKembalian() {
            var totalBayar = parseInt($('#total_cost').text().replace(/[^0-9]/g, ''));
            var inputBayar = parseInt($('#input_payment').val().replace(/\./g, '')) || 0;
            var return_payment = totalBayar - inputBayar;
            $('#return_payment').val(formatRupiah(return_payment));
        }
    });
</script>

{{-- simpan --}}
<script>
    $(document).ready(function() {
        $('#form-edit-order').submit(function(e) {
            e.preventDefault();

            const tombolSave = $('#btn-save-order'); // Tombol Simpan
            const iconSave = tombolSave.find('i'); // Ikon dalam tombol

            // Ganti ikon tombol dengan spinner dan nonaktifkan tombol
            iconSave.removeClass('fas fa-save').addClass('fas fa-spinner fa-spin');
            tombolSave.prop('disabled', true); // Nonaktifkan tombol agar tidak bisa diklik dua kali

            // Ambil nilai status, supplier_id, cash_id, dan type_payment
            var status = $('input[name="status"]:checked').val();
            var supplierId = $('#supplier_id').val();
            var cashId = $('#cash_id').val();
            var typePayment = $('#type_payment').val(); // Ambil nilai type_payment

            // Ambil nilai amount dari cash_id yang dipilih
            var cashAmount = $('#cash_id option:selected').data('amount');

            // Validasi jika status "Lunas" dan supplier_id kosong
            if ((status === 'Lunas') && !supplierId) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Mohon pilih supplier.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    tombolSave.prop('disabled', false); // Mengaktifkan kembali tombol
                    iconSave.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula
                });
                return; // Hentikan proses submit jika validasi gagal
            }

            // Validasi jika cash_id kosong untuk status "Lunas"
            if (status === 'Lunas' && !cashId) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Mohon pilih Kas Pembayaran.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    tombolSave.prop('disabled', false); // Mengaktifkan kembali tombol
                    iconSave.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula
                });
                return; // Hentikan proses submit jika validasi gagal
            }

            // Validasi jika type_payment kosong untuk status "Lunas"
            if (status === 'Lunas' && !typePayment) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Mohon pilih jenis pembayaran.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    tombolSave.prop('disabled', false); // Mengaktifkan kembali tombol
                    iconSave.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula
                });
                return; // Hentikan proses submit jika validasi gagal
            }

            // Validasi jika total_cost lebih besar dari cashAmount
            var totalBayar = parseInt($('#total_cost').text().replace(/[^0-9]/g, ''));
            if (totalBayar > cashAmount) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Saldo Kas tidak mencukupi.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    tombolSave.prop('disabled', false); // Mengaktifkan kembali tombol
                    iconSave.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula
                });
                return; // Hentikan proses submit jika validasi gagal
            }

            const formData = new FormData(this); // Ambil data formulir

            $.ajax({
                url: "{{ route('orders.update', $order->id) }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Kembalikan status tombol dan ikon setelah sukses
                    tombolSave.prop('disabled', false); // Aktifkan tombol kembali
                    iconSave.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula

                    Swal.fire('Sukses!', response.message, 'success').then(() => {
                        window.location.href = "{{ route('orders.index') }}"; // Redirect setelah sukses
                    });
                },
                error: function(xhr) {
                    // Kembalikan status tombol dan ikon setelah error
                    tombolSave.prop('disabled', false); // Aktifkan tombol kembali
                    iconSave.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula

                    Swal.fire('Error!', 'Terjadi kesalahan saat menyimpan.', 'error');
                }
            });
        });
    });
</script>







@endpush