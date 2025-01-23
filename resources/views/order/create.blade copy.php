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
    <!-- <div class="card bg-light-info shadow-none position-relative overflow-hidden" style="border: solid 0.5px #ccc;">
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
    </div> -->


    <div class="card-body">
        <!-- Section Tutorial -->
        <div class="card mb-1" id="tutorial-section">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0" style="color: white;">Cara Menggunakan Halaman Penjualan</h5>
                <!-- Tombol Close -->
                <button type="button" class="btn-close btn-close-white" aria-label="Close" onclick="closeTutorial()"></button>
            </div>
            <div class="card-body">





                <ol>
                    <li>
                        Tentukan status transaksi, jika akan melakukan pesanan penjualan maka pilih status transaksi Pesanan Penjualan, lalu jika sudah terjadi penjualan kepada pelanggan, maka sesuaikan/edit dengan item penjualan yang real.
                    </li>
                    <li>
                        Untuk transaksi penjualan yang <b>sudah diterima barang oleh pelanggan dan sudah diselesaikan pembayaran</b>, maka pilih status transaksi Lunas.
                    </li>
                    <li>
                        Sisa transaksi diluar Pesanan Penjualan dan Lunas silahkan pilih antara <b>Belum Lunas dan Pending</b>
                    </li>
                    <li>
                        Jika Status Transaksi <b>Lunas</b>, maka Pelanggan, Kas dan Jenis Pembayaran <b>Wajib Diisi</b> sehingga transaksi tersebut akan mengurangi Kas serta menambah Kuantiti Produk yang dipilih
                    </li>
                    <li>
                        Pilih <b>Pelanggan</b> dari dropdown yang tersedia untuk menentukan kepada siapa barang dijual. Form ini memungkinkan Anda memilih pelanggan yang sudah terdaftar. Pilih pelanggan yang relevan atau tambahkan pelanggan baru jika diperlukan. Untuk Pelanggan yang sifatnya tentatif bisa memilih <b>Pelanggan Umum</b>
                    </li>
                    <li>
                        Cari dan pilih <b>Produk</b> yang ingin dijual, lalu masukkan jumlah (Qty). Pilih produk dari daftar yang telah tersedia dan tentukan jumlah barang yang ingin dijual. Pastikan jumlah yang dimasukkan sesuai dengan stok yang ada.
                    </li>
                    <li>
                        Untuk Pencarian Produk bisa menggunakan beberapa cara, yaitu <b>Barcode</b> (Kursor harus berada pada inputan Barcode), <b>Item Produk</b> Pada list yang terdapat Gambar dan juga mencari produk melalui <b>Dropdown Produk</b>.
                    </li>
                    <li>
                        Untuk <b>Potongan Diskon</b> bisa menggunakan persentase % dan juga diskon jumlah (nilai)
                    </li>
                    <li>
                        Pilih metode <b>Jenis Pembayaran</b>, apakah <b>TUNAI</b> atau <b>TRANSFER</b>. Pilih metode pembayaran yang digunakan oleh pelanggan. Jika pelanggan memilih pembayaran dengan tunai, pilih "TUNAI". Jika menggunakan transfer, pilih "TRANSFER".
                    </li>
                    <li>
                        Jika pembayaran dilakukan dengan <b>TRANSFER</b>, unggah bukti pembayaran dengan memilih file gambar di bagian <b>Gambar</b>. Jika pembayaran menggunakan transfer, Anda perlu mengunggah bukti pembayaran dalam bentuk gambar (misalnya screenshot transfer). Pilih file gambar yang sesuai dari perangkat Anda <b>(Opsional)</b>.
                    </li>
                    <li>
                        Setelah semua informasi lengkap, klik tombol <b>Simpan</b> untuk menyimpan data penjualan. Setelah memastikan semua data sudah terisi dengan benar, klik tombol "Simpan" untuk menyimpan transaksi penjualan Anda.
                    </li>
                </ol>
                <p class="text-muted">
                    Pastikan semua informasi telah diisi dengan benar sebelum menyimpan transaksi. Untuk membatalkan, Anda dapat menekan tombol <b>Kembali</b>.
                </p>

            </div>
        </div>
        <!-- End of Section Tutorial -->
    </div>
    <div class="card">
        <button class="btn btn-primary" id="showTutorialBtn" onclick="toggleTutorial()"><i class="fa fa-eye"></i> Lihat Informasi</button>
    </div>












    <section class="datatables mt-1">
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


                        <div class="col-md-12 mb-3">
                            <div class="form-group">
                                <input style="background-color: yellow;" type="text" class="form-control" id="barcode" name="barcode" placeholder="Scan barcode produk" autofocus>
                            </div>
                        </div>

                        <hr>

                        <form id="form-order" action="{{ route('orders.store') }}" method="post" enctype="multipart/form-data">

                            @csrf
                            <h5 class="card-title mb-0"><b style="color: blue;">Kode Penjualan :
                                    <input type="hidden" id="no_order" name="no_order" value="{{ $no_order }}">{{ $no_order }}</input></b></h5>
                            <hr>
                            <div class="form-group mb-3 col-md-6">
                                <label for="status" class="mb-2">Status Transaksi :</label><br>
                                <label style="margin-right: 6px;" title="Pesanan Penjualan: Transaksi ini sedang dalam tahap pesanan awal untuk penjualan. Barang atau jasa telah dipesan oleh pelanggan, tetapi pembayaran belum sepenuhnya dilakukan. (Pintasan: Alt+P)">
                                    <input type="radio" name="status" value="Pesanan Penjualan" id="status-pesanan" checked>
                                    Pesanan Penjualan <b>(Alt+P)</b>
                                </label>
                                <label style="margin-right: 6px;" title="Lunas: Semua pembayaran terkait transaksi ini telah diselesaikan. Tidak ada saldo atau kewajiban yang tersisa. (Pintasan: Alt+L)">
                                    <input type="radio" name="status" value="Lunas" id="status-lunas">
                                    Lunas <b>(Alt+L)</b>
                                </label>
                                <label style="margin-right: 6px;" title="Belum Lunas: Sebagian pembayaran telah dilakukan, tetapi masih ada sisa yang harus dilunasi. (Pintasan: Alt+B)">
                                    <input type="radio" name="status" value="Belum Lunas" id="status-belum-lunas">
                                    Belum Lunas <b>(Alt+B)</b>
                                </label>
                                <label style="margin-right: 6px;" title="Pending: Transaksi ini sedang menunggu tindakan atau konfirmasi lebih lanjut. Belum ada keputusan final. (Pintasan: Alt+N)">
                                    <input type="radio" name="status" value="Pending" id="status-pending">
                                    Pending <b>(Alt+N)</b>
                                </label>
                            </div>




                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="hari">Tanggal Penjualan </label>
                                        <input type="date" class="form-control" id="order_date"
                                            name="order_date" value="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="customer_id">Pelanggan</label>
                                        <select class="form-control" id="customer_id" name="customer_id">
                                            @foreach ($data_customers as $index => $customerItem)
                                            <option value="{{ $customerItem->id }}"
                                                data-customer-category-id="{{ $customerItem->customer_category_id }}"
                                                {{ $index === 0 ? 'selected' : '' }}>
                                                {{ $customerItem->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="name" id="name">
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
                                                data-stock="{{ $produkItem->stock }}"
                                                data-barcode="{{ $produkItem->barcode }}">
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
                                <tbody></tbody>
                            </table>

                            <!-- Total Bayar dan Input Bayar -->
                            <div class="row mt-4">
                                <div class="col-md-6 mb-3">
                                    <h5 style="color: red; font-size:30px;" class="badge badge-danger"><b>Total
                                            Bayar: </b> <span id="total_cost">0</span></h5>
                                    <input type="hidden" name="total_cost" class="form-control total_cost">
                                    <input type="hidden" name="total_cost_before" id="total_cost_before" class="form-control total_cost_before">
                                    <hr>
                                    <h1 style="color: red" id="info_payment"></h1>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="percent_discount">Diskon (%):</label>
                                        <input type="number" class="form-control" id="percent_discount" name="percent_discount" min="0" max="100" value="0">
                                    </div>
                                    <div class="form-group">
                                        <label for="amount_discount">Diskon (Jumlah):</label>
                                        <input type="text" class="form-control" id="amount_discount" name="amount_discount" value="0">
                                    </div>

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
                                            class="fas fa-save"></i> Simpan (F8)</button>
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


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil status tutorial dari server
        fetch('/tutorial-status')
            .then(response => response.json())
            .then(data => {
                if (data.tutorialClosed) {
                    // Jika tutorial sudah ditutup, sembunyikan card dan tampilkan tombol "Munculkan Informasi"
                    document.getElementById('tutorial-section').style.display = 'none';
                    document.getElementById('showTutorialBtn').style.display = 'block';
                } else {
                    // Jika tutorial masih terbuka, tampilkan card tutorial
                    document.getElementById('tutorial-section').style.display = 'block';
                    document.getElementById('showTutorialBtn').style.display = 'none';
                }
            });
    });

    // Fungsi untuk menutup tutorial dan menyimpan statusnya
    function closeTutorial() {
        // Menyimpan status tutorial ke file JSON
        fetch('/set-tutorial-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    tutorialClosed: true
                })
            })
            .then(response => response.json())
            .then(data => {
                // Sembunyikan card tutorial dan tampilkan tombol "Munculkan Informasi"
                document.getElementById('tutorial-section').style.display = 'none';
                document.getElementById('showTutorialBtn').style.display = 'block';
            });
    }

    // Fungsi untuk menampilkan tutorial kembali
    function toggleTutorial() {
        // Menyimpan status tutorial ke file JSON
        fetch('/set-tutorial-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    tutorialClosed: false
                })
            })
            .then(response => response.json())
            .then(data => {
                // Tampilkan card tutorial dan sembunyikan tombol
                document.getElementById('tutorial-section').style.display = 'block';
                document.getElementById('showTutorialBtn').style.display = 'none';
            });
    }
</script>



<script>
    $(document).ready(function() {
        // Event listener untuk shortcut keyboard
        $(document).on('keydown', function(e) {
            if (e.altKey) {
                switch (e.key.toLowerCase()) {
                    case 'p': // Alt + P untuk Pesanan Penjualan
                        $('#status-pesanan').prop('checked', true).focus();
                        break;
                    case 'l': // Alt + L untuk Lunas
                        $('#status-lunas').prop('checked', true).focus();
                        break;
                    case 'b': // Alt + B untuk Belum Lunas
                        $('#status-belum-lunas').prop('checked', true).focus();
                        break;
                    case 'n': // Alt + D untuk Pending
                        $('#status-pending').prop('checked', true).focus();
                        break;
                }
            }
        });
    });
</script>

<script>
    $(document).ready(function() {



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
    $(document).ready(function() {
        var no_order = "{{ $no_order }}";
        $('#no_order').text(no_order);

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

        $('#customer_id').on('change', function() {
            var selectedCustomerId = $(this).val();
            var selectedCustomerCategoryId = $('#customer_id option:selected').data('customer-category-id');
            $('#name').val(selectedCustomerCategoryId);
        });

        $('#barcode').on('change', function() {
            var scannedBarcode = $(this).val();
            var customerCategoryId = $('#name').val(); // Ambil kategori customer

            if (scannedBarcode) {
                $.ajax({
                    url: '/get-product-by-barcode', // Endpoint untuk mencari produk berdasarkan barcode
                    method: 'GET',
                    data: {
                        barcode: scannedBarcode
                    },
                    success: function(response) {
                        if (response && response.product) {
                            var product = response.product;
                            var productId = product.id;
                            var productName = product.name;
                            var productStock = product.stock;
                            var productPrice = product.cost_price; // Default price

                            // Cek apakah ada kategori customer
                            if (customerCategoryId) {
                                $.ajax({
                                    url: '/get-product-price',
                                    method: 'GET',
                                    data: {
                                        product_id: productId,
                                        customer_category_id: customerCategoryId
                                    },
                                    success: function(priceResponse) {
                                        if (priceResponse && priceResponse.price) {
                                            productPrice = priceResponse.price; // Update harga sesuai kategori customer
                                        }
                                        updateCartRow(productId, productName, productStock, productPrice);
                                        $('#barcode').val(''); // Kosongkan input barcode setelah berhasil
                                    },
                                    error: function() {
                                        alert("Gagal mendapatkan harga berdasarkan kategori customer.");
                                    }
                                });
                            } else {
                                updateCartRow(productId, productName, productStock, productPrice);
                                $('#barcode').val(''); // Kosongkan input barcode setelah berhasil
                            }
                        } else {
                            alert("Produk dengan barcode tersebut tidak ditemukan!");
                        }
                    },
                    error: function() {
                        alert("Terjadi kesalahan saat mencari produk.");
                    }
                });
            }
        });



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
        // Event listener ketika dropdown produk berubah
        $('#product_id').on('change', function() {
            // Ambil nilai dari option yang dipilih
            var selectedProductId = $(this).val();
            var selectedProductName = $('#product_id option:selected').text();
            var selectedProductStock = $('#product_id option:selected').data('stock');
            var selectedProductPrice = $('#product_id option:selected').data('order-price');
            var customerCategoryId = $('#name').val(); // Ambil ID kategori pelanggan

            // Debugging untuk memastikan nilai diambil dengan benar
            console.log("Selected Product ID:", selectedProductId);
            console.log("Selected Product Name:", selectedProductName);
            console.log("Selected Product Stock:", selectedProductStock);
            console.log("Selected Product Price:", selectedProductPrice);

            // Validasi nilai stok
            if (isNaN(selectedProductStock)) {
                console.error("Stock is not valid:", selectedProductStock);
                selectedProductStock = 0; // Set nilai default jika tidak valid
            }

            if (customerCategoryId) {
                // Jika ada kategori pelanggan, ambil harga dari server
                $.ajax({
                    url: '/get-product-price', // Endpoint untuk mengambil harga
                    method: 'GET',
                    data: {
                        product_id: selectedProductId,
                        customer_category_id: customerCategoryId
                    },
                    success: function(response) {
                        console.log("Response from server:", response); // Debug response dari server

                        if (response && response.price) {
                            selectedProductPrice = response.price; // Update harga dari server
                        } else {
                            console.log("Price is missing in response");
                        }

                        // Update row di tabel
                        updateCartRow(selectedProductId, selectedProductName, selectedProductStock, selectedProductPrice);
                    },
                    error: function() {
                        console.error("Error fetching price from server");
                    }
                });
            } else {
                // Jika tidak ada kategori pelanggan, langsung update tabel
                updateCartRow(selectedProductId, selectedProductName, selectedProductStock, selectedProductPrice);
            }
        });

        // Fungsi untuk menambahkan atau memperbarui baris di tabel
        function updateCartRow(productId, productName, productStock, productPrice) {
            console.log("Updating cart row with:", productId, productName, productStock, productPrice);

            // Pastikan harga adalah angka sebelum diformat
            if (isNaN(productPrice)) {
                console.error("Product Price is not valid:", productPrice);
                productPrice = 0; // Set ke default jika tidak valid
            }

            // Cek apakah produk sudah ada di tabel
            var existingProductRow = $('#scroll_hor tbody tr').filter(function() {
                return $(this).find('input[name="product_id[]"]').val() == productId;
            });

            if (existingProductRow.length > 0) {
                // Jika sudah ada, tambahkan jumlahnya
                var quantityInput = existingProductRow.find('.quantity');
                var currentQty = parseInt(quantityInput.val());
                if (currentQty < productStock) {
                    quantityInput.val(currentQty + 1);
                } else {
                    alert("Stok produk sudah habis!");
                }
            } else {
                // Jika belum ada, tambahkan baris baru
                var newRow = `
            <tr>
                <td></td>
                <td style="text-align:left;">
                    <input type="hidden" name="product_id[]" value="${productId}">
                    <label>${productName}</label>
                </td>
                <td>
                    <input type="text" class="form-control cost_price" name="cost_price[]" value="${formatRupiah(productPrice)}">
                </td>
                <td>
                    <input type="text" class="form-control stock" name="stock[]" value="${productStock}" readonly>
                </td>
                <td>
                    <input type="number" class="form-control quantity" name="quantity[]" value="1" max="${productStock}">
                </td>
                <td>
                    <input type="text" class="form-control total" name="total[]" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm btn-remove-product"><i class="fas fa-trash"></i></button>
                </td>
            </tr>`;

                $('#scroll_hor tbody').append(newRow);
            }

            // Perbarui nomor baris dan total
            updateRowNumbers();
            calculateTotal();
        }

        // Fungsi untuk memperbarui nomor urut baris di tabel
        function updateRowNumbers() {
            $('#scroll_hor tbody tr').each(function(index, row) {
                $(row).find('td:first').text(index + 1);
            });
        }

        // Fungsi untuk menghitung total harga di tabel
        function calculateTotal() {
            var totalBayar = 0;
            $('#scroll_hor tbody tr').each(function() {
                var quantity = parseInt($(this).find('.quantity').val()) || 0;
                var hargaBeli = parseInt($(this).find('.cost_price').val().replace(/\./g, '').replace(/[^0-9]/g, '')) || 0;
                var total = quantity * hargaBeli;
                $(this).find('.total').val(formatRupiah(total));
                totalBayar += total;
            });

            // Set nilai total_cost_before sebelum diskon
            $('#total_cost_before').val(totalBayar);

            // Hitung diskon
            var discountPercent = parseFloat($('#percent_discount').val()) || 0;
            var discountAmount = parseInt($('#amount_discount').val().replace(/\./g, '').replace(/[^0-9]/g, '')) || 0;

            var discountFromPercent = totalBayar * (discountPercent / 100);
            var totalAfterDiscount = totalBayar - discountFromPercent - discountAmount;

            // Update total bayar
            $('#total_cost').text(formatRupiah(totalAfterDiscount));
            $('.total_cost').val(totalAfterDiscount < 0 ? 0 : totalAfterDiscount);
        }



        $(document).on('input', '.quantity, .cost_price, #percent_discount, #amount_discount', function() {
            if ($(this).attr('id') === 'amount_discount') {
                $(this).val(formatRupiah($(this).val().replace(/\./g, '')));
            }
            calculateTotal();
        });


        $(document).on('input', '.quantity', function() {
            calculateTotal();
        });

        $(document).on('input', '.cost_price', function() {
            $(this).val(formatRupiah($(this).val().replace(/\./g, '')));
            calculateTotal();
        });

        $(document).on('click', '.btn-remove-product', function() {
            $(this).closest('tr').remove();
            calculateTotal();
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
        // Fungsi untuk submit form
        function submitForm() {
            const tombolSimpan = $('#btn-save-order');
            const iconSimpan = tombolSimpan.find('i'); // Mengambil ikon di dalam tombol

            // Ganti ikon tombol dengan ikon loading
            iconSimpan.removeClass('fas fa-save').addClass('fas fa-spinner fa-spin');
            tombolSimpan.prop('disabled', true); // Menonaktifkan tombol agar tidak bisa diklik dua kali

            // Validasi dan pengambilan data
            var status = $('input[name="status"]:checked').val();
            var customerId = $('#customer_id').val();
            var cashId = $('#cash_id').val();
            var typePayment = $('#type_payment').val();
            var inputPayment = parseFloat($('#input_payment').val().replace(/[^0-9]/g, '')) || 0;
            var totalCost = parseFloat($('#total_cost').text().replace(/[^0-9]/g, '')) || 0;

            if ((status === 'Lunas') && !customerId) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Mohon pilih customer.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    tombolSimpan.prop('disabled', false);
                    iconSimpan.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save');
                });
                return;
            }

            if (status === 'Lunas' && !cashId) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Mohon pilih Kas Pembayaran.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    tombolSimpan.prop('disabled', false);
                    iconSimpan.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save');
                });
                return;
            }

            if (status === 'Lunas' && !typePayment) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Mohon pilih jenis pembayaran.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    tombolSimpan.prop('disabled', false);
                    iconSimpan.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save');
                });
                return;
            }

            if (status === 'Lunas' && inputPayment < totalCost) {
                Swal.fire({
                    title: 'Kurang Bayar!',
                    text: 'Jumlah pembayaran lebih kecil dari total biaya.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {
                    tombolSimpan.prop('disabled', false);
                    iconSimpan.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save');
                });
                return;
            }

            var formData = new FormData($('#form-order')[0]);

            $.ajax({
                url: "{{ route('orders.store') }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    Swal.fire({
                        title: 'Sukses!',
                        text: response.message,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: '<i class="fas fa-print"></i> Cetak Struk',  // Ikon untuk "Cetak Struk"
                        cancelButtonText: '<i class="fas fa-times"></i> Tidak, Terima Kasih',  // Ikon untuk "Tidak, Terima Kasih"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Jika memilih "Cetak Struk", arahkan ke route print_struk dengan ID transaksi
                            window.location.href = "{{ url('/orders') }}/" + response.order_id + "/print-struk";
                        } else {
                            // Jika memilih "Tidak", reload halaman
                            window.location.href = "{{ route('orders.create') }}";
                        }
                    });
                },

                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menyimpan data.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        tombolSimpan.prop('disabled', false);
                        iconSimpan.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save');
                    });
                    console.error(error);
                }
            });
        }

        // Event listener untuk submit form ketika tombol simpan diklik
        $('#form-order').submit(function(e) {
            e.preventDefault();
            submitForm();
        });

        // Shortcut keyboard untuk tombol F8
        $(document).on('keydown', function(e) {
            if (e.key === 'F8') {
                e.preventDefault();
                submitForm();
            }
        });
    });
</script>







@endpush