@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('template/back') }}/dist/libs/select2/dist/css/select2.min.css">
<style>
    @media print {

        /* Sembunyikan elemen sidebar dan menu */
        .sidebar,
        .navbar,
        .breadcrumb,
        .card.bg-light-info {
            display: none;
        }

        /* Tampilkan hanya kontainer utama */
        .container-fluid {
            width: 100%;
            padding: 0;
        }

        /* Mengurangi ukuran gambar saat dicetak */
        .card-body img {
            max-width: 50%;
            /* Menyesuaikan ukuran gambar */
            margin-top: 10px;
        }

        /* Atur lebar tabel agar lebih rapih */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th,
        table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        /* Perbaiki ukuran font dan spasi untuk mencetak */
        h3,
        h4 {
            font-size: 16px;
        }

        .breadcrumb {
            font-size: 12px;
        }
    }

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
                        <form>
                            <div class="form-group mb-3">
                                <label for="opname_date">Tanggal Stock Opname</label>
                                <input type="text" class="form-control" id="opname_date" value="{{ $data_stock_opname->opname_date }}" readonly>
                            </div>

                            <div class="form-group mb-3">
                                <label for="description">Deskripsi</label>
                                <textarea class="form-control" id="description" readonly>{{ $data_stock_opname->description }}</textarea>
                            </div>

                            @if($data_stock_opname->image)
                            <div class="form-group mb-3">
                                <label for="image">Gambar</label>
                                <br>
                                <img src="{{ asset('upload/stock_opname/'.$data_stock_opname->image) }}" alt="Gambar Stock Opname" style="max-width: 10%; margin-top: 10px;">
                            </div>
                            @endif


                            <h3 class="mb-3 mt-3">Produk yang Diperiksa</h3>
                            <table id="scroll_hor" class="table border table-striped table-bordered display nowrap" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>Nama Produk</th>
                                        <th>Stock Sistem</th>
                                        <th>Stock Real</th>
                                        <th>Selisih</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_products as $product)
                                    @php
                                    $detail = $data_stock_opname->stockOpnameDetails->firstWhere('product_id', $product->id);
                                    @endphp
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>{{ $detail ? $detail->physical_stock : '0' }}</td>
                                        <td>{{ $detail ? $detail->difference : '0' }}</td>
                                        <td>{{ $detail ? $detail->description_detail : '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <a href="{{ route('stock_opname.print', $data_stock_opname->id) }}" class="btn btn-secondary">
                                <i class="fas fa-print"></i> Cetak
                            </a>


                            <a href="{{ route('stock_opname.index') }}" class="btn btn-danger" style="color:white;">
                                <i class="fas fa-step-backward"></i> Kembali
                            </a>
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
    // Function to trigger print
    document.querySelector('button[type="button"]').addEventListener('click', function() {
        window.print();
    });
</script>
@endpush