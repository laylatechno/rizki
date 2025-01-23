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

                        <form id="form-purchase" action="{{ route('purchases.store') }}" method="post" enctype="multipart/form-data">

                            @csrf
                            <h5 class="card-title mb-0"><b style="color: blue;">Kode Pembelian : <input type="hidden"
                                        id="no_purchase" name="no_purchase"
                                        value="{{ $no_purchase }}">{{ $no_purchase }}</input></b></h5>

                            <br>
                            <hr>
                            <div class="form-group mb-3 col-md-6">
                                <label for="status" class="mb-2"> Status Transaksi : </label><br>
                                <label style="margin-right: 6px;" title="Pesanan Pembelian: Transaksi ini sedang dalam tahap pesanan awal. Barang atau jasa telah dipesan, namun pembayaran belum sepenuhnya dilakukan atau diselesaikan.">
                                    <input type="radio" name="status" value="Pesanan Pembelian" checked> Pesanan Pembelian
                                </label>
                                <label style="margin-right: 6px;" title="Lunas: Semua pembayaran terkait transaksi ini telah diselesaikan. Tidak ada saldo atau kewajiban yang tersisa.">
                                    <input type="radio" name="status" value="Lunas"> Lunas
                                </label>
                                <label style="margin-right: 6px;" title="Belum Lunas: Sebagian pembayaran telah dilakukan, tetapi masih ada sisa yang harus dilunasi.">
                                    <input type="radio" name="status" value="Belum Lunas"> Belum Lunas
                                </label>
                                <label style="margin-right: 6px;" title="Pending: Transaksi ini sedang menunggu tindakan atau konfirmasi lebih lanjut. Belum ada keputusan final.">
                                    <input type="radio" name="status" value="Pending"> Pending
                                </label>
                            </div>



                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="hari">Tanggal Pembelian </label>
                                        <span class="text-danger">*</span>
                                        <input type="date" class="form-control" id="purchase_date"
                                            name="purchase_date" value="<?php echo date('Y-m-d'); ?>" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="supplier_id">Supplier </label>
                                        <select class="form-control" id="supplier_id" name="supplier_id">
                                            <option value="">--Pilih Supplier--</option>
                                            @foreach ($data_suppliers as $supplierItem)
                                            <option value="{{ $supplierItem->id }}"
                                                data-nama-supplier="{{ $supplierItem->name }}">
                                                {{ $supplierItem->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="name" id="name">
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label for="product_id">Cari Produk </label>
                                        <span class="text-danger">*</span>
                                        <select class="form-control" id="product_id" name="product_id" required>
                                            <option value="">--Pilih Produk--</option>
                                            @foreach ($data_products as $produkItem)
                                            <option value="{{ $produkItem->id }}"
                                                data-purchase-price="{{ $produkItem->purchase_price }}">
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
                            </div>

                            <table id="scroll_hor"
                                class="table border table-striped table-bordered display nowrap"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th style="text-align: left;">Produk</th>
                                        <th>Harga</th>
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
                                            Bayar: </b> Rp.<span id="total_cost">0</span></h5>
                                    <input type="hidden" name="total_cost" id="" class="form-control total_cost">
                                    <hr>
                                    <h1 style="color: red" id="info_payment"></h1>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group" hidden>
                                        <label for="input_payment">Bayar:</label>
                                        <input type="text" class="form-control" id="input_payment" name="input_payment">
                                    </div>
                                    <div class="form-group" hidden>
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
                                    <button type="submit" class="btn btn-success" style="color:white;" id="btn-save-purchase"><i
                                            class="fas fa-save"></i> Simpan</button>
                                    <a href="{{ route('purchases.index') }}" class="btn btn-danger" style="color:white;"><i
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
        $('#supplier_id').select2();
        $('#product_id').select2();
        $('#cash_id').select2();
    });
</script>
<script>
    $(document).ready(function() {

        var no_purchase = "{{ $no_purchase }}";
        // Menampilkan kode pembelian dalam elemen span dengan id "no_purchase"
        $('#no_purchase').text(no_purchase);



        // Function to format harga rupiah with separator ribuan
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



        // Function to calculate total
        function calculateTotal() {
            var totalBayar = 0;
            $('#scroll_hor tbody tr').each(function() {
                var quantity = parseInt($(this).find('.quantity').val()) || 0;
                var hargaBeli = parseInt($(this).find('.purchase_price').val().replace(/\./g, '').replace(
                    /[^0-9]/g, '')) || 0;
                var total = quantity * hargaBeli;
                $(this).find('.total').val(formatRupiah(total));
                totalBayar += total;
            });
            $('#total_cost').text(formatRupiah(totalBayar));
            $('.total_cost').val(formatRupiah(totalBayar));
        }
        // Event listener untuk perubahan pada input quantity
        $(document).on('input', '.quantity', function() {
            calculateTotal();
        });




        // Event listener untuk perubahan pada input harga beli
        $(document).on('input', '.purchase_price', function() {
            // Memanggil fungsi untuk menambahkan separator ribuan
            $(this).val(formatRupiah($(this).val().replace(/\./g, '')));
            calculateTotal(); // Menghitung total setelah perubahan harga beli
        });




        // Event listener untuk perubahan pada dropdown product_id
        $('#product_id').on('change', function() {
            var selectedProductId = $(this).val(); // Ambil nilai produk yang dipilih dari dropdown
            var selectedProductName = $('#product_id option:selected')
                .text(); // Ambil nama produk yang dipilih
            var selectedProductPrice = $('#product_id option:selected').data(
                'purchase-price'); // Ambil harga beli produk yang dipilih

            // Cek apakah produk sudah ada dalam tabel
            var existingProductRow = $('#scroll_hor tbody tr').filter(function() {
                return $(this).find('input[name="product_id[]"]').val() == selectedProductId;
            });

            if (existingProductRow.length > 0) {
                // Jika produk sudah ada, tambahkan jumlah quantity-nya
                var quantityInput = existingProductRow.find('.quantity');
                var currentQty = parseInt(quantityInput.val());
                quantityInput.val(currentQty + 1);
            } else {
                // Jika produk belum ada, tambahkan baris baru ke tabel
                var newRow = '<tr>' +
                    '<td></td>' + // Nomor
                    '<td style="text-align:left;">' +
                    '<input type="hidden" name="product_id[]" value="' + selectedProductId + '">' +
                    '<label>' + selectedProductName + '</label></td>' + // Nama Produk sebagai label
                    '<td><input type="text" class="form-control purchase_price" name="purchase_price[]" value="' +
                    formatRupiah(selectedProductPrice) + '"></td>' +
                    // Harga Beli dengan pemisah ribuan
                    '<td><input type="number" class="form-control quantity" name="quantity[]" value="1"></td>' +
                    // Qty
                    '<td><input type="text" class="form-control total" name="total[]" readonly></td>' +
                    // Total
                    '<td><button type="button" class="btn btn-danger btn-sm btn-remove-product"><i class="fas fa-trash"></i></button></td>' +
                    // Tombol Hapus
                    '</tr>';

                $('#scroll_hor tbody').append(newRow);

            }

            // Update nomor pada setiap baris
            updateRowNumbers();

            // Hitung total bayar keseluruhan
            calculateTotal();
        });


        // Fungsi untuk memperbarui nomor pada setiap baris tabel
        function updateRowNumbers() {
            $('#scroll_hor tbody tr').each(function(index, row) {
                $(row).find('td:first').text(index + 1); // Nomor dimulai dari 1
            });
        }

        // Event listener untuk tombol Hapus Produk
        $(document).on('click', '.btn-remove-product', function() {
            $(this).closest('tr').remove(); // Hapus baris produk
            updateRowNumbers(); // Update nomor pada setiap baris
            calculateTotal();
        });



        // Event listener untuk input bayar
        $('#input_payment').on('input', function() {
            var inputBayar = $(this).val().replace(/[^\d]/g, '');
            $(this).val(formatRupiah(inputBayar));
            hitungKembalian(); // Hitung kembali return_payment setiap kali input berubah
            tampilkanInfoPembayaranKurang(); // Tampilkan informasi pembayaran kurang jika perlu
        });

        // Fungsi untuk menampilkan informasi pembayaran kurang
        function tampilkanInfoPembayaranKurang() {
            var totalBayar = parseInt($('#total_cost').text().replace(/[^0-9]/g, ''));
            var inputBayar = parseInt($('#input_payment').val().replace(/\./g, '')) || 0;
            if (inputBayar < totalBayar) {
                $('#info_payment').text('Bayar masih kurang');
            } else {
                $('#info_payment').text('');
            }
        }


        // Fungsi untuk menghitung return_payment
        function hitungKembalian() {
            var totalBayar = parseInt($('#total_cost').text().replace(/[^0-9]/g, ''));
            var inputBayar = parseInt($('#input_payment').val().replace(/\./g, '')) ||
                0; // Gunakan 0 jika inputBayar tidak valid
            var return_payment = totalBayar - inputBayar;
            $('#return_payment').val(formatRupiah(return_payment));
        }

        // Event listener untuk tombol hapus
        $(document).on('click', '.delete-row', function() {
            var rowTotal = parseInt($(this).closest('tr').find('.total').text().replace(/[^0-9]/g, ''));
            var totalBayar = parseInt($('#total_cost').text().replace(/[^0-9]/g, ''));
            var newTotalBayar = totalBayar - rowTotal;
            $('#total_cost').text(formatRupiah(newTotalBayar));

            var inputBayar = parseInt($('#input_payment').val().replace(/\./g, '')) || 0;
            var return_payment = inputBayar - newTotalBayar;
            $('#return_payment').val(formatRupiah(return_payment));

            $(this).closest('tr').remove();
        });

        // Event listener untuk perubahan pada input quantity
        $(document).on('input', '.quantity', function() {
            var quantity = $(this).val();
            var hargaBeli = $(this).closest('tr').find('input[name="purchase_price[]"]').val().replace(
                /\./g, '');
            var total = hargaBeli * quantity;
            $(this).closest('tr').find('input[name="total[]"]').val(formatRupiah(total));

            // Hitung total bayar keseluruhan
            var totalBayar = 0;
            $('#scroll_hor tbody tr').each(function() {
                var rowTotal = parseInt($(this).find('input[name="total[]"]').val().replace(
                    /\./g, '') || 0);
                totalBayar += rowTotal;
            });

            // Tampilkan total bayar di luar tabel
            $('#total_cost').text(formatRupiah(totalBayar));

            // Hitung return_payment
            hitungKembalian();
        });


        // Ambil nilai awal dari elemen span
        var totalBayarSpan = document.getElementById("total_cost");
        var totalBayarInput = document.querySelector(".total_cost");
        totalBayarInput.value = totalBayarSpan.innerText;

        // Update nilai input saat nilai di elemen span berubah
        totalBayarSpan.addEventListener("input", function() {
            totalBayarInput.value = totalBayarSpan.innerText;
        });

        // Update nilai elemen span saat nilai input berubah
        totalBayarInput.addEventListener("input", function() {
            totalBayarSpan.innerText = totalBayarInput.value;
        });


        $(document).on('select2:open', () => {
            document.querySelector('.select2-search__field').focus();
        });
    });
</script>

{{-- simpan --}}
<script>
    $(document).ready(function() {
        $('#form-purchase').submit(function(e) {
            e.preventDefault();
            const tombolSimpan = $('#btn-save-purchase');
            const iconSimpan = tombolSimpan.find('i'); // Mengambil ikon di dalam tombol

            // Ganti ikon tombol dengan ikon loading
            iconSimpan.removeClass('fas fa-save').addClass('fas fa-spinner fa-spin');
            tombolSimpan.prop('disabled', true); // Menonaktifkan tombol agar tidak bisa diklik dua kali

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
                    tombolSimpan.prop('disabled', false); // Mengaktifkan kembali tombol
                    iconSimpan.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula
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
                    tombolSimpan.prop('disabled', false); // Mengaktifkan kembali tombol
                    iconSimpan.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula
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
                    tombolSimpan.prop('disabled', false); // Mengaktifkan kembali tombol
                    iconSimpan.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula
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
                    tombolSimpan.prop('disabled', false); // Mengaktifkan kembali tombol
                    iconSimpan.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula
                });
                return; // Hentikan proses submit jika validasi gagal
            }

            // Jika semua validasi lolos, lanjutkan ke pengiriman data dengan AJAX
            var formData = new FormData(this); // Menggunakan FormData untuk mengambil data formulir

            $.ajax({
                url: "{{ route('purchases.store') }}",
                type: 'POST',
                data: formData,
                processData: false, // Mengabaikan pemrosesan data otomatis
                contentType: false, // Mengabaikan pengaturan tipe konten otomatis
                success: function(response) {
                    // Validasi total_cost > input_payment
                    var inputBayar = parseInt($('#input_payment').val().replace(/\./g, '')) || 0;
                    if (totalBayar > inputBayar) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Input bayar harus lebih besar atau sama dengan total bayar.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            tombolSimpan.prop('disabled', false); // Mengaktifkan kembali tombol
                            iconSimpan.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula
                        });
                    } else {
                        Swal.fire({
                            title: 'Sukses!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.href = "{{ route('purchases.create') }}";
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menyimpan data.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        tombolSimpan.prop('disabled', false); // Mengaktifkan kembali tombol
                        iconSimpan.removeClass('fas fa-spinner fa-spin').addClass('fas fa-save'); // Kembalikan ikon semula
                    });
                    console.error(error);
                }
            });
        });
    });
</script>






@endpush