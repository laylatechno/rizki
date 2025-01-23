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
                                <a class="text-muted text-decoration-none" href="{{ route('purchases.index') }}">Halaman Pembelian</a>
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

    <div class="card-body">
        <!-- Section Tutorial -->
        <div class="card mb-1" id="tutorial-section">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0" style="color: white;">Cara Menggunakan Halaman Pembelian</h5>
                <!-- Tombol Close -->
                <button type="button" class="btn-close btn-close-white" aria-label="Close" onclick="closeTutorial()"></button>
            </div>
            <div class="card-body">
                <ol>
                    <li>
                        Tentukan status transaksi, jika akan melakukan Purchase Order atau pesanan pembelian maka pilih status transaksi Pesanan Pembelian, lalu jika sudah terjadi pembelian di supplier, maka sesuaikan/edit dengan item pembelian yang real. 
                    </li>
                    <li>
                        Untuk transaksi pembelian yang <b>sudah diterima barang dan sudah diselesaikan pembayaran</b>, maka pilih status transaksi Lunas.  
                    </li>
                    <li>
                        Sisa transaksi diluar Pesanan Pembelian dan Lunas silahkan pilih antara <b>Belum Lunas dan Pending</b>    
                    </li>
                    <li>
                        Jika Status Transaksi <b>Lunas</b>, maka Supplier, Kas dan Jenis Pembayaran <b>Wajib Diisi</b> sehingga transaksi tersebut akan mengurangi Kas serta menambah Kuantiti Produk yang dipilih    
                    </li>
                    <li>
                        Pilih <b>Supplier</b> dari dropdown yang tersedia untuk menentukan dari mana barang dibeli. Untuk Supplier yang sifatnya tentatif bisa memilih <b>Supplier Umum</b>
                    </li>
                    <li>
                        Cari dan pilih <b>Produk</b> yang ingin dibeli, lalu masukkan jumlah (Qty).
                    </li>
                    <li>
                        Pilih metode <b>Jenis Pembayaran</b>, apakah <b>CASH</b> atau <b>TRANSFER</b>.
                    </li>
                    <li>
                        Jika pembayaran dilakukan dengan <b>TRANSFER</b>, unggah bukti pembayaran dengan memilih file gambar di bagian <b>Gambar</b> (Opsional).
                    </li>
                    <li>
                        Setelah semua informasi lengkap, klik tombol <b>Simpan</b> untuk menyimpan data pembelian.
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
        <button class="btn btn-primary" id="showTutorialBtn" onclick="toggleTutorial()">Lihat Informasi</button>
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





                        <form id="form-edit-purchase" enctype="multipart/form-data">
                            @csrf
                            @method('PUT') {{-- Laravel Method Spoofing untuk method PUT --}}

                            <h5 class="card-title mb-0"><b style="color: blue;">Kode Pembelian : <input type="hidden"
                                        id="no_purchase" name="no_purchase"
                                        value="{{ $purchase->no_purchase }}">{{ $purchase->no_purchase }}</input></b></h5>

                            <br>
                            <hr>
                            <div class="form-group mb-3 col-md-6">
                                <label for="status" class="mb-2"> Status Pembayaran : </label><br>
                                <label style="margin-right: 6px;">
                                    <input type="radio" name="status" value="Pesanan Pembelian" {{ old('status', $purchase->status) == 'Pesanan Pembelian' ? 'checked' : '' }}> Pesanan Pembelian
                                </label>
                                <label style="margin-right: 6px;">
                                    <input type="radio" name="status" value="Lunas" {{ old('status', $purchase->status) == 'Lunas' ? 'checked' : '' }}> Lunas
                                </label>
                                <label style="margin-right: 6px;">
                                    <input type="radio" name="status" value="Belum Lunas" {{ old('status', $purchase->status) == 'Belum Lunas' ? 'checked' : '' }}> Belum Lunas
                                </label>
                                <label style="margin-right: 6px;">
                                    <input type="radio" name="status" value="Pending" {{ old('status', $purchase->status) == 'Pending' ? 'checked' : '' }}> Pending
                                </label>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="hari">Tanggal Pembelian</label>
                                        <span class="text-danger">*</span>
                                        <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', $purchase->purchase_date ?? date('Y-m-d')) }}">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="supplier_id">Supplier</label>
                                        <select class="form-control" id="supplier_id" name="supplier_id">
                                            <option value="">--Pilih Supplier--</option>
                                            @foreach ($data_suppliers as $supplierItem)
                                            <option value="{{ $supplierItem->id }}"
                                                {{ old('supplier_id', $purchase->supplier_id) == $supplierItem->id ? 'selected' : '' }}>
                                                {{ $supplierItem->name }}
                                            </option>
                                            @endforeach
                                        </select>
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
                                            <option value="{{ $product->id }}" data-name="{{ $product->name }}" data-purchase-price="{{ $product->purchase_price }}">
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
                                                {{ old('cash_id', $purchase->cash_id) == $cash->id ? 'selected' : '' }}
                                                data-amount="{{ $cash->amount }}">
                                                {{ $cash->name }} - Rp{{ number_format($cash->amount, 0, ',', '.') }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                {{-- Tabel Produk --}}
                                <table class="table table-bordered" id="cart-table">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th width="15%">Qty</th>
                                            <th>Total</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($purchase->purchaseItems as $index => $item)
                                        <tr data-id="{{ $item->product_id }}">
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">
                                                {{ $item->product->name }}
                                            </td>
                                            <td>
                                                <input type="text" class="form-control purchase_price" name="purchase_price[]" value="{{ $item->purchase_price }}">
                                            </td>
                                            <td>
                                                <input type="number" class="form-control quantity" name="quantity[]" value="{{ $item->quantity }}">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control total" name="total[]" value="{{ $item->purchase_price * $item->quantity }}" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm btn-remove-product"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <div class="row mt-4">
                                    <div class="col-md-6 mb-3">
                                        <h5 style="color: red; font-size:30px;" class="badge badge-danger"><b>Total
                                                Bayar: </b> Rp.<span id="total_cost">{{ number_format(old('total_cost', $purchase->total_cost ?? 0), 0, ',', '.') }}</span></h5>
                                        <input type="hidden" name="total_cost" id="total_cost_input" class="form-control total_cost"
                                            value="{{ old('total_cost', $purchase->total_cost ?? 0) }}">
                                        <hr>

                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <div class="form-group" hidden>
                                            <label for="input_payment">Bayar:</label>
                                            <input type="text" class="form-control" id="input_payment" name="input_payment" value="{{ old('input_payment', $purchase->input_payment ?? '') }}">
                                        </div>
                                        <div class="form-group" hidden>
                                            <label for="return_payment">Kembalian:</label>
                                            <input type="text" class="form-control" id="return_payment" name="return_payment" readonly value="{{ old('return_payment', $purchase->return_payment ?? '') }}">
                                        </div>


                                        <div class="form-group mb-3">
                                            <label for="type_payment">Jenis Pembayaran:</label>
                                            <select name="type_payment" id="type_payment" class="form-control">
                                                <option value="">--Pilih Jenis Pembayaran--</option>
                                                <option value="CASH" {{ old('type_payment', $purchase->type_payment) == 'CASH' ? 'selected' : '' }}>CASH</option>
                                                <option value="TRANSFER" {{ old('type_payment', $purchase->type_payment) == 'TRANSFER' ? 'selected' : '' }}>TRANSFER</option>
                                            </select>
                                        </div>

                                        <div class="form-group mb-3" id="image_container">
                                            <label for="image">Gambar:</label>
                                            <input type="file" class="form-control" id="image" name="image" onchange="previewImage()">
                                            <canvas id="preview_canvas" style="display: none; max-width: 80%; margin-top: 10px;"></canvas>
                                            <img id="preview_image" src="{{ old('image', $purchase->image) ? asset('storage/' . $purchase->image) : '#' }}" alt="Preview Logo" style="display: none; max-width: 80%; margin-top: 10px;">
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
                                            <textarea name="description" id="description" class="form-control" cols="30" rows="3">{{ old('description', $purchase->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-top">
                                    <div class="card-body">
                                        <button type="submit" class="btn btn-success" style="color:white;" id="btn-save-purchase">
                                            <i class="fas fa-save"></i> Simpan
                                        </button>
                                        <a href="{{ route('purchases.index') }}" class="btn btn-danger" style="color:white;">
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
        $('#supplier_id').select2();
        $('#product_id').select2();
        $('#cash_id').select2();
    });
</script>

<script>
    $(document).ready(function() {
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

        // Fungsi untuk menghitung total
        function calculateTotal() {
            var totalBayar = 0;

            // Iterasi setiap baris pada tabel
            $('#cart-table tbody tr').each(function() {
                var $row = $(this); // Mengambil baris saat ini
                var quantity = parseInt($row.find('.quantity').val()) || 0;
                var hargaBeli = parseInt($row.find('.purchase_price').val().replace(/\./g, '')) || 0;

                // Hitung total per baris
                var total = quantity * hargaBeli;
                $row.find('.total').val(formatRupiah(total)); // Set nilai total pada input

                // Tambahkan ke totalBayar
                totalBayar += total;
            });

            // Set nilai total keseluruhan
            $('#total_cost').text(formatRupiah(totalBayar));
            $('.total_cost').val(totalBayar); // Jika nilai ingin disimpan sebagai angka (bukan teks Rupiah)
        }

        // Panggil calculateTotal setiap kali quantity atau purchase_price diubah
        $(document).on('input', '.quantity, .purchase_price', function() {
            calculateTotal(); // Hitung ulang total saat ada perubahan
        });

        // Tambahkan produk ke tabel
        $('#product_id').change(function() {
            const selectedOption = $(this).find(':selected');
            const productId = selectedOption.val();
            const productName = selectedOption.data('name');
            const purchasePrice = parseFloat(selectedOption.data('purchase-price')) || 0;

            if (!productId) return;

            // Cek apakah produk sudah ada
            const existingRow = $(`table tbody tr[data-id="${productId}"]`);
            if (existingRow.length) {
                const quantityInput = existingRow.find('.quantity');
                const newQuantity = parseInt(quantityInput.val() || 0) + 1;
                quantityInput.val(newQuantity);

                const totalInput = existingRow.find('.total');
                totalInput.val((newQuantity * purchasePrice).toFixed(2));
            } else {
                $('table tbody').append(`
                    <tr data-id="${productId}">
                        <td>${$('table tbody tr').length + 1}</td>
                        <td>
                            <input type="hidden" name="product_id[]" value="${productId}">
                            ${productName}
                        </td>
                        <td>
                            <input type="text" class="form-control purchase_price" name="purchase_price[]" value="${purchasePrice}">
                        </td>
                        <td>
                            <input type="number" class="form-control quantity" name="quantity[]" value="1">
                        </td>
                        <td>
                            <input type="text" class="form-control total" name="total[]" value="${purchasePrice}" readonly>
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-remove-product"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                `);
            }
            $(this).val('');
            calculateTotal(); // Hitung total setelah menambah produk
        });

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
    $(document).ready(function() {
        $('#form-edit-purchase').submit(function(e) {
            e.preventDefault();

            const tombolSave = $('#btn-save-purchase'); // Tombol Simpan
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
                url: "{{ route('purchases.update', $purchase->id) }}",
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Kembalikan status tombol dan ikon setelah sukses
                    tombolSave.prop('disabled', false); // Aktifkan tombol kembali
                    iconSave.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula

                    Swal.fire('Sukses!', response.message, 'success').then(() => {
                        window.location.href = "{{ route('purchases.index') }}"; // Redirect setelah sukses
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