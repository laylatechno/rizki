@extends('layouts.app')
@section('title', $title)
@section('subtitle', $subtitle)

@push('css')
<link rel="stylesheet" href="{{ asset('template/back/dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
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
                        <div class="table-responsive">
                            <div class="row mb-4">
                                <div class="col-md-12">

                                    <form method="GET" action="{{ route('report.top_product_reports') }}" class="mb-4">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="start_date" class="form-label">Tanggal Awal</label>
                                                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date', now()->format('Y-m-d')) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="end_date" class="form-label">Tanggal Akhir</label>
                                                <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date', now()->format('Y-m-d')) }}">
                                            </div>
                                            <div class="col-md-3">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" id="status" class="form-select">
                                                    <option value="">Semua Status</option>
                                                    <option value="Pesanan Penjualan" {{ request('status') == 'Pesanan Penjualan' ? 'selected' : '' }}>
                                                        Pesanan Penjualan
                                                    </option>
                                                    <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                                                    <option value="Belum Lunas" {{ request('status') == 'Belum Lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                </select>
                                            </div>
                                            <!-- Filter Kategori Transaksi -->
                                            <div class="col-md-3">
                                                <label for="category" class="form-label">Kategori Produk</label>
                                                <select name="category" id="category" class="form-select">
                                                    <option value="">Semua Kategori</option>
                                                    @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                        </div>
                                        <div class="row mt-3">
                                            <div class="col">
                                                <div class="d-flex flex-wrap gap-2">
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fa fa-filter"></i> Filter
                                                    </button>
                                                    <a href="{{ route('report.top_product_reports') }}" class="btn btn-secondary">
                                                        <i class="fa fa-sync"></i> Reset
                                                    </a>
                                                    <a href="{{ route('report.top_product_reports.export', [
                    'start_date' => request('start_date', now()->format('Y-m-d')),
                    'end_date' => request('end_date', now()->format('Y-m-d')),
                    'cash_id' => request('cash_id'),
                    'category' => request('category')
                ]) }}" class="btn btn-primary">
                                                        <i class="fa fa-file-excel"></i> Export Excel
                                                    </a>
                                                    <a href="{{ route('report.top_product_reports.export_pdf', [
                    'start_date' => request()->start_date,
                    'end_date' => request()->end_date,
                    'cash_id' => request()->cash_id,
                    'category' => request()->category
                ]) }}" class="btn btn-danger">
                                                        <i class="fa fa-file-pdf"></i> Export PDF
                                                    </a>
                                                    <a href="{{ route('report.top_product_reports.preview_pdf', [
                    'start_date' => request('start_date', now()->format('Y-m-d')),
                    'end_date' => request('end_date', now()->format('Y-m-d')),
                    'cash_id' => request('cash_id'),
                    'category' => request('category')
                ]) }}" class="btn btn-info" target="_blank">
                                                        <i class="fa fa-eye"></i> Preview PDF
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <table id="scroll_hor" class="table border table-striped table-bordered display nowrap" style="width: 100%">
                                        <thead>
                                            <tr>
                                                <th width="3%">No</th>
                                                <th>Nama Produk</th>
                                                <th>Kategori</th>
                                                <th>Quantity</th>
                                                <th>Jumlah Rupiah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topProduct as $product)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $product->product->name ?? 'N/A' }}</td>
                                                <td>{{ $product->product->category->name ?? 'N/A' }}</td>
                                                <td>{{ $product->total_quantity }}</td>
                                                <td>{{ number_format($product->total_price, 0, ',', '.') }}</td>
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
<script src="{{ asset('template/back/dist/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('template/back/dist/js/datatable/datatable-basic.init.js') }}"></script>
<script src="{{ asset('template/back') }}/dist/libs/select2/dist/js/select2.full.min.js"></script>
<script src="{{ asset('template/back') }}/dist/libs/select2/dist/js/select2.min.js"></script>
<script src="{{ asset('template/back') }}/dist/js/forms/select2.init.js"></script>

<script>
    $(document).ready(function() {


        $('#category').select2({
            placeholder: "Pilih Kategori",
            allowClear: true,
            width: '100%'
        });
    });
</script>

@endpush