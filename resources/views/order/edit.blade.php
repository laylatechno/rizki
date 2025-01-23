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


                        <div class="col-md-12 mb-3 mt-3">
                            <div class="form-group">
                                <input style="background-color: yellow;" type="text" class="form-control" id="barcode" name="barcode" placeholder="Scan barcode produk" autofocus>
                            </div>
                        </div>

                        <hr>


                        <form id="form-edit-order" enctype="multipart/form-data">
                            @csrf
                            @method('PUT') {{-- Laravel Method Spoofing untuk method PUT --}}

                            <h5 class="card-title mb-0"><b style="color: blue;">Kode Penjualan : <input type="hidden"
                                        id="no_order" name="no_order"
                                        value="{{ $order->no_order }}">{{ $order->no_order }}</input></b></h5>

                            <hr>
                            <div class="form-group mb-3 col-md-6">
                                <label for="status" class="mb-2"> Status Pembayaran : </label><br>
                                <label style="margin-right: 6px;">
                                    <input type="radio" name="status"   id="status-pesanan" value="Pesanan Penjualan" {{ old('status', $order->status) == 'Pesanan Penjualan' ? 'checked' : '' }}> Pesanan Penjualan <b>(Alt+P)</b>
                                </label>
                                <label style="margin-right: 6px;">
                                    <input type="radio" name="status" id="status-lunas" value="Lunas" {{ old('status', $order->status) == 'Lunas' ? 'checked' : '' }}> Lunas <b>(Alt+L)</b>
                                </label>
                                <label style="margin-right: 6px;">
                                    <input type="radio" name="status" id="status-belum-lunas" value="Belum Lunas" {{ old('status', $order->status) == 'Belum Lunas' ? 'checked' : '' }}> Belum Lunas  <b>(Alt+B)</b>
                                </label>
                                <label style="margin-right: 6px;">
                                    <input type="radio" name="status" id="status-pending" value="Pending" {{ old('status', $order->status) == 'Pending' ? 'checked' : '' }}> Pending <b>(Alt+N)</b>
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

                                {{-- Produk --}}
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="product_id">Cari Produk</label>
                                        <span class="text-danger">*</span>
                                        <select class="form-control product-select" id="product_id">
                                            <option value="" disabled selected>-- Pilih Produk --</option>
                                            @foreach ($data_products as $product)
                                            <option value="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-order-price="{{ $product->cost_price }}"
                                                data-stock="{{ $product->stock }}"
                                                data-barcode="{{ $product->barcode }}">
                                                {{ $product->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="cash_id">Kas Pembayaran</label>
                                        <select name="cash_id" id="cash_id" class="form-control">
                                            <option value="">--Pilih Cash--</option>
                                            @foreach($data_cashes as $cash)
                                            <option value="{{ $cash->id }}"
                                                {{ old('cash_id', $order->cash_id) == $cash->id ? 'selected' : '' }}
                                                data-amount="{{ $cash->amount }}">
                                                {{ $cash->name }} - Rp{{ number_format($cash->amount, 0, ',', '.') }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="product-list mb-4">
                                    @foreach ($data_products as $produkItem)
                                    <div class="product-item"
                                        data-id="{{ $produkItem->id }}"
                                        data-name="{{ $produkItem->name }}"
                                        data-stock="{{ $produkItem->stock }}"
                                        data-price="{{ $produkItem->cost_price }}">
                                        <img src="/upload/products/{{ $produkItem->image }}" alt="{{ $produkItem->name }}" />
                                        <p>{{ $produkItem->name }}</p>
                                    </div>

                                    @endforeach
                                </div>

                                {{-- Tabel Produk --}}
                                <div>
                                    <table class="table table-bordered" id="cart-table">
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
                                            <tr data-id="{{ $item->product_id }}" data-stock="{{ $item->product->stock }}">
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                                                    {{ $item->product->name }}
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control order_price" name="order_price[]" value="{{ $item->order_price }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control stock" name="stock[]" value="{{ $item->product->stock }}" readonly>
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
                                </div>


                                <div class="row mt-4">
                                    <div class="col-md-6 mb-3">
                                        <h5 style="color: red; font-size:30px;" class="badge badge-danger"><b>Total Bayar: </b> Rp.
                                            <span id="total_cost">{{ number_format(old('total_cost', $order->total_cost ?? 0), 0, ',', '.') }}</span>
                                        </h5>
                                        <input type="hidden" name="total_cost" id="total_cost_input" class="form-control total_cost"
                                            value="{{ old('total_cost', $order->total_cost ?? 0) }}">
                                        <input type="" name="total_cost_before" id="total_cost_before_input" class="form-control total_cost_before"
                                            value="{{ old('total_cost_before', $order->total_cost_before ?? 0) }}">
                                        <hr>
                                        <h1 style="color: red" id="info_payment"></h1>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group">
                                            <label for="percent_discount">Diskon (%):</label>
                                            <input
                                                type="number"
                                                class="form-control"
                                                id="percent_discount"
                                                name="percent_discount"
                                                min="0"
                                                max="100"
                                                value="{{ old('percent_discount', $order->percent_discount ?? '0') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="amount_discount">Diskon (Jumlah):</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="amount_discount"
                                                name="amount_discount"
                                                value="{{ old('amount_discount', $order->amount_discount ?? '0') }}">
                                        </div>
                                        <div class="form-group">
                                            <label for="input_payment">Bayar:</label>
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="input_payment"
                                                name="input_payment"
                                                value="{{ old('input_payment', $order->input_payment ?? '') }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="return_payment">Kembalian:</label>
                                            <input type="text" class="form-control" id="return_payment" name="return_payment" readonly value="{{ old('return_payment', $order->return_payment ?? '') }}">
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="type_payment">Jenis Pembayaran:</label>
                                            <select name="type_payment" id="type_payment" class="form-control">
                                                <option value="">--Pilih Jenis Pembayaran--</option>
                                                <option value="CASH" {{ old('type_payment', $order->type_payment) == 'CASH' ? 'selected' : '' }}>CASH</option>
                                                <option value="TRANSFER" {{ old('type_payment', $order->type_payment) == 'TRANSFER' ? 'selected' : '' }}>TRANSFER</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3" id="image_container">
                                            <label for="image">Gambar:</label>
                                            <input type="file" class="form-control" id="image" name="image" onchange="previewImage()">
                                            <canvas id="preview_canvas" style="display: none; max-width: 80%; margin-top: 10px;"></canvas>
                                            <img id="preview_image" src="{{ old('image', $order->image) ? asset('storage/' . $order->image) : '#' }}" alt="Preview Logo" style="display: none; max-width: 80%; margin-top: 10px;">
                                        </div>
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

                                        <div class="form-group mb-3">
                                            <label for="description">Keterangan:</label>
                                            <textarea name="description" id="description" class="form-control" cols="30" rows="3">{{ old('description', $order->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-top">
                                    <div class="card-body">
                                        <button type="submit" class="btn btn-success" style="color:white;" id="btn-save-order">
                                            <i class="fas fa-save"></i> Simpan
                                        </button>
                                        <a href="{{ route('orders.index') }}" class="btn btn-danger" style="color:white;">
                                            <i class="fas fa-step-backward"></i> Kembali
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

@push('script')
<script src="{{ asset('template/back') }}/dist/libs/select2/dist/js/select2.full.min.js"></script>
<script src="{{ asset('template/back') }}/dist/libs/select2/dist/js/select2.min.js"></script>



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

        // Panggil calculateTotal setiap kali quantity atau order_price diubah
        $(document).on('input', '.quantity, .order_price', function() {
            calculateTotal(); // Hitung ulang total saat ada perubahan
        });

        // Panggil calculateTotal setiap kali diskon berubah
        $(document).on('input', '#percent_discount, #amount_discount', function() {
            calculateTotal(); // Hitung ulang total saat diskon berubah
        });
        // Fungsi untuk validasi perubahan manual pada input quantity
        $(document).on('change', '.quantity', function() {
            const quantityInput = $(this);
            const currentQty = parseInt(quantityInput.val() || 0);
            const row = quantityInput.closest('tr'); // Ambil baris terkait
            const productStock = parseInt(row.data('stock') || 0); // Ambil stok dari atribut data-stock

            // Validasi apakah jumlah melebihi stok
            if (currentQty > productStock) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Jumlah produk tidak boleh melebihi stok yang tersedia.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                quantityInput.val(productStock); // Reset ke stok maksimal
            } else if (currentQty <= 0) {
                Swal.fire({
                    title: 'Peringatan!',
                    text: 'Jumlah produk harus minimal 1.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                quantityInput.val(1); // Reset ke nilai minimal
            }

            // Update total harga setelah validasi
            const orderPrice = parseFloat(row.find('.order_price').val().replace(/[^0-9.-]+/g, '') || 0);
            row.find('.total').val(formatRupiah(currentQty * orderPrice));

            // Recalculate total harga semua produk
            calculateTotal();
        });



        // Fungsi untuk menambahkan produk ke dalam tabel
        $('.product-item').click(function() {
            const productId = $(this).data('id');
            const productName = $(this).data('name');
            const productStock = $(this).data('stock');
            const customerCategoryId = $('#customer_id').val(); // Ambil kategori pelanggan

            if (!productId) return;

            // Lakukan AJAX untuk mendapatkan harga produk berdasarkan kategori pelanggan
            $.ajax({
                url: '/get-product-price', // URL controller
                method: 'GET',
                data: {
                    product_id: productId,
                    customer_category_id: customerCategoryId
                },
                success: function(response) {
                    const orderPrice = parseFloat(response.price) || 0;

                    // Cek apakah produk sudah ada di tabel
                    const existingRow = $(`table#cart-table tbody tr[data-id="${productId}"]`);
                    if (existingRow.length) {
                        const quantityInput = existingRow.find('.quantity');
                        const currentQty = parseInt(quantityInput.val() || 0);
                        if (currentQty < productStock) {
                            const newQuantity = currentQty + 1;
                            quantityInput.val(newQuantity);
                            const totalInput = existingRow.find('.total');
                            totalInput.val(formatRupiah(newQuantity * orderPrice)); // Update total
                        } else {
                            alert("Stok produk sudah habis!");
                        }
                    } else {
                        // Jika produk belum ada, tambahkan baris baru ke tabel
                        $('table#cart-table tbody').append(`
                            <tr data-id="${productId}" data-stock="${productStock}">
                                <td>${$('table#cart-table tbody tr').length + 1}</td>
                                <td>
                                    <input type="hidden" name="product_id[]" value="${productId}">
                                    ${productName}
                                </td>
                                <td>
                                    <input type="text" class="form-control order_price" name="order_price[]" value="${formatRupiah(orderPrice)}">
                                </td>
                                <td>
                                    <input type="number" class="form-control stock" name="stock[]" value="${productStock}" readonly>
                                </td>
                                <td>
                                    <input type="number" class="form-control quantity" name="quantity[]" value="1">
                                </td>
                                <td>
                                    <input type="text" class="form-control total" name="total[]" value="${formatRupiah(orderPrice)}" readonly>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-product"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        `);


                    }

                    // Hitung total setelah produk ditambahkan
                    calculateTotal();
                },
                error: function() {
                    alert('Gagal mengambil harga produk.');
                }
            });
        });



        // Tambahkan produk ke tabel
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

            if (isNaN(productPrice)) {
                console.error("Product Price is not valid:", productPrice);
                productPrice = 0;
            }

            // Cek apakah produk sudah ada di tabel
            var existingProductRow = $('#cart-table tbody tr').filter(function() {
                return $(this).find('input[name="product_id[]"]').val() == productId;
            });

            if (existingProductRow.length > 0) {
                // Tambahkan jumlahnya
                var quantityInput = existingProductRow.find('.quantity');
                var currentQty = parseInt(quantityInput.val());
                if (currentQty < productStock) {
                    quantityInput.val(currentQty + 1);
                } else {
                    alert("Stok produk sudah habis!");
                }
            } else {
                // Tambahkan baris baru
                var newRow = `
                    <tr>
                        <td></td>
                        <td style="text-align:left;">
                            <input type="hidden" name="product_id[]" value="${productId}">
                            <label>${productName}</label>
                        </td>
                        <td>
                            <input type="text" class="form-control order_price" name="order_price[]" value="${formatRupiah(productPrice)}">
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

                $('#cart-table tbody').append(newRow);
                console.log("Baris baru ditambahkan:", newRow);
            }

            updateRowNumbers();
            calculateTotal();
        }


        function updateRowNumbers() {
            $('#scroll_hor tbody tr').each(function(index, row) {
                $(row).find('td:first').text(index + 1);
            });
        }


        // Fungsi untuk format Rupiah
        function formatRupiah(angka) {
            var numberString = angka.toString().replace(/[^,\d]/g, ''),
                split = numberString.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            return rupiah;
        }

        function calculateTotal() {
            var totalBayar = 0; // Total setelah perhitungan diskon
            var totalCostBefore = 0; // Total sebelum diskon atau pengurangan

            // Iterasi setiap baris pada tabel
            $('#cart-table tbody tr').each(function() {
                var $row = $(this);
                var quantity = parseInt($row.find('.quantity').val()) || 0;
                var hargaBeli = parseInt($row.find('.order_price').val().replace(/\./g, '')) || 0;

                // Hitung total per baris
                var total = quantity * hargaBeli;
                $row.find('.total').val(formatRupiah(total)); // Tampilkan total di kolom "total"

                // Tambahkan ke total keseluruhan untuk totalCostBefore
                totalCostBefore += total;
            });

            // Tambahkan logika diskon (jika ada)
            var discountPercent = parseFloat($('#percent_discount').val()) || 0;
            var discountAmount = parseInt($('#amount_discount').val().replace(/\./g, '')) || 0;

            // Perhitungan diskon
            var discountValue = totalCostBefore * (discountPercent / 100);
            totalBayar = totalCostBefore - discountValue - discountAmount;

            // Update nilai total ke elemen
            $('#total_cost').text(formatRupiah(totalBayar));
            $('.total_cost').val(totalBayar < 0 ? 0 : totalBayar); // Simpan angka
            $('.total_cost_before').val(totalCostBefore); // Simpan total sebelum diskon

            // Pastikan total cost yang sudah terpotong diskon dikirim saat submit
            $('#total_cost_input').val(totalBayar < 0 ? 0 : totalBayar); // Update input hidden dengan total yang terpotong diskon
        }

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



        // Hapus produk dari tabel dan hitung total
        $(document).on('click', '.btn-remove-product', function() {
            $(this).closest('tr').remove();
            $('table tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
            calculateTotal(); // Hitung ulang total setelah produk dihapus
        });
    });
</script>



<script>
    $(document).ready(function () {
        // Tambahkan listener untuk shortcut F8
        $(document).on('keydown', function (e) {
            if (e.key === 'F8') {
                e.preventDefault(); // Mencegah aksi default tombol F8
                $('#form-edit-order').submit(); // Trigger form submit
            }
        });

        $('#form-edit-order').submit(function (e) {
            e.preventDefault();

            const tombolSave = $('#btn-save-order'); // Tombol Simpan
            const iconSave = tombolSave.find('i'); // Ikon dalam tombol

            // Ganti ikon tombol dengan spinner dan nonaktifkan tombol
            iconSave.removeClass('fas fa-save').addClass('fas fa-spinner fa-spin');
            tombolSave.prop('disabled', true); // Nonaktifkan tombol agar tidak bisa diklik dua kali

            // Ambil nilai status, customer_id, cash_id, dan type_payment
            var status = $('input[name="status"]:checked').val();
            var customerId = $('#customer_id').val();
            var cashId = $('#cash_id').val();
            var typePayment = $('#type_payment').val(); // Ambil nilai type_payment

            // Ambil nilai total bayar dan input pembayaran, hilangkan separator, lalu ubah menjadi angka
            var totalCost = parseFloat($('#total_cost').text().replace(/[^0-9]/g, '')) || 0; // Mengambil teks, hanya angka
            var inputPayment = parseFloat($('#input_payment').val().replace(/[^0-9]/g, '')) || 0; // Mengambil input, hanya angka

            // Validasi jika jumlah pembayaran kurang dari total biaya
            if (inputPayment < totalCost) {
                Swal.fire({
                    title: 'Kurang Bayar!',
                    text: 'Jumlah pembayaran tidak boleh kurang dari total biaya.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                }).then(() => {
                    tombolSave.prop('disabled', false); // Mengaktifkan kembali tombol
                    iconSave.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula
                });
                return; // Hentikan proses submit jika validasi gagal
            }

            // Validasi jika status "Lunas" dan customer_id kosong
            if ((status === 'Lunas') && !customerId) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Mohon pilih customer.',
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

            const formData = new FormData(this); // Ambil data formulir

            $.ajax({
                url: "{{ route('orders.update', $order->id) }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // Kembalikan status tombol dan ikon setelah sukses
                    tombolSave.prop('disabled', false); // Aktifkan tombol kembali
                    iconSave.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula

                    Swal.fire('Sukses!', response.message, 'success').then(() => {
                        window.location.href = "{{ route('orders.index') }}"; // Redirect setelah sukses
                    });
                },
                error: function (xhr) {
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