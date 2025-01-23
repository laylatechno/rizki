@extends('layouts.app')
@section('title', $title)
@section('subtitle', $subtitle)

@push('css')
<link rel="stylesheet" href="{{ asset('template/back/dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
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

    <div class="card-body">
        <!-- Section Tutorial -->
        <div class="card mb-1" id="tutorial-section">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0" style="color: white;">Informasi Halaman Produk</h5>
                <!-- Tombol Close -->
                <button type="button" class="btn-close btn-close-white" aria-label="Close" onclick="closeTutorial()"></button>
            </div>
            <div class="card-body">
                <ol>
                    <li>
                        Pilih <b>Tambah Data</b> untuk mengisi produk baru.
                    </li>
                    <li>
                        Pilih <b>Generate Barcode</b> untuk membuat barcode berdasarkan kode produk dan silahkan pilih/checklist terlebih dahulu produk-produk yang akan digenerate
                    </li>
                </ol>
             
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
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-lg-12 margin-tb">
                                    @can('product-create')
                                    <div class="pull-right">
                                        <a class="btn btn-success mb-2" href="{{ route('products.create') }}"><i class="fa fa-plus"></i> Tambah Data</a>
                                        <button class="btn btn-info mb-2" id="generate-barcode-btn" onclick="submitGenerateBarcodeForm()">
                                            <i class="fa fa-barcode"></i> Generate Barcode
                                        </button>
                                    </div>

                                    @endcan

                                </div>
                            </div>

                            <form id="generate-barcode-form" action="{{ route('products.generate_barcode') }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="selected_ids" id="selected_ids">
                            </form>
                            <script>
                                function submitGenerateBarcodeForm() {
                                    let selected = [];
                                    document.querySelectorAll('.row-checkbox:checked').forEach(checkbox => {
                                        selected.push(checkbox.value);
                                    });

                                    if (selected.length === 0) {
                                        alert('Pilih setidaknya satu produk untuk generate barcode.');
                                        return;
                                    }

                                    document.getElementById('selected_ids').value = selected.join(',');
                                    document.getElementById('generate-barcode-form').submit();
                                }
                            </script>


                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            @endif

                            <script>
                                function toggleCheckboxes(source) {
                                    let checkboxes = document.querySelectorAll('.row-checkbox');
                                    checkboxes.forEach(checkbox => {
                                        checkbox.checked = source.checked;
                                    });
                                }
                            </script>

                            <table id="scroll_hor" class="table border table-striped table-bordered display nowrap" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th width="5px">
                                            <input type="checkbox" id="check_all" onclick="toggleCheckboxes(this)">
                                        </th>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Kode Produk</th>
                                        <th>Barcode</th>
                                        <th>Deskripsi</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Stok</th>
                                        <th>Gambar</th>
                                        <th width="280px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_products as $p)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="row-checkbox" value="{{ $p->id }}">
                                        </td>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $p->name }}</td>
                                        <td>{{ $p->code_product }}</td>
                                        <td>
                                            {!! !empty($p->barcode) ? \App\Helpers\BarcodeHelper::generateBarcode($p->barcode) : '<span>No Data</span>' !!}
                                        </td>


                                        <td>{{ $p->description ?: 'No Data' }}</td>
                                        <td>Rp {{ number_format($p->purchase_price, 0, ',', '.') }}</td>
                                        <td>Rp {{ number_format($p->cost_price, 0, ',', '.') }}</td>
                                        <td>{{ $p->stock }}</td>
                                        <td>
                                            <a href="/upload/products/{{ $p->image }}" target="_blank">
                                                <img style="max-width:50px; max-height:50px" src="/upload/products/{{ $p->image }}" alt="">
                                            </a>
                                        </td>
                                        <td>
                                            <a class="btn btn-warning btn-sm" href="{{ route('products.show', $p->id) }}"><i class="fa fa-eye"></i> Show</a>
                                            @can('product-edit')
                                            <a class="btn btn-primary btn-sm" href="{{ route('products.edit', $p->id) }}"><i class="fa fa-edit"></i> Edit</a>
                                            @endcan
                                            @can('product-delete')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $p->id }})"><i class="fa fa-trash"></i> Delete</button>
                                            <form id="delete-form-{{ $p->id }}" method="POST" action="{{ route('products.destroy', $p->id) }}" style="display:none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            @endcan
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('script')



<script>
    function confirmDelete(roleId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + roleId).submit();
            }
        });
    }
</script>

<script src="{{ asset('template/back/dist/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/back/dist/js/datatable/datatable-basic.init.js') }}"></script>



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



@endpush