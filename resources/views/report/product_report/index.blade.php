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

                                    <form action="{{ route('report.product_reports') }}" method="GET" class="mb-4">
                                        <!-- Row untuk Inputan -->
                                        <div class="row">
                                            <!-- Kolom Tanggal Awal -->
                                            <div class="col-md-2">
                                                <label for="start_date" class="form-label">Tanggal Awal</label>
                                                <input type="date" name="start_date" id="start_date" class="form-control"
                                                    value="{{ request('start_date', now()->format('Y-m-d')) }}">
                                            </div>

                                            <!-- Kolom Tanggal Akhir -->
                                            <div class="col-md-2">
                                                <label for="end_date" class="form-label">Tanggal Akhir</label>
                                                <input type="date" name="end_date" id="end_date" class="form-control"
                                                    value="{{ request('end_date', now()->format('Y-m-d')) }}">
                                            </div>


                                            <!-- Kolom Filter Status -->
                                            <div class="col-md-2">
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

                                            <!-- Kolom Filter Pelanggan -->
                                            <div class="col-md-3">
                                                <label for="customer_id" class="form-label">Pelanggan</label>
                                                <select name="customer_id[]" id="customer_id" class="form-select" multiple>
                                                    @foreach ($customers as $customer)
                                                    <option value="{{ $customer->id }}"
                                                        {{ (is_array(request('customer_id')) && in_array($customer->id, request('customer_id'))) ? 'selected' : '' }}>
                                                        {{ $customer->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Kolom Filter Produk -->
                                            <div class="col-md-3">
                                                <label for="product_id" class="form-label">Produk</label>
                                                <select name="product_id[]" id="product_id" class="form-select" multiple>
                                                    @foreach ($data_products as $product)
                                                    <option value="{{ $product->id }}"
                                                        {{ (is_array(request('product_id')) && in_array($product->id, request('product_id'))) ? 'selected' : '' }}>
                                                        {{ $product->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>



                                        </div>

                                        <!-- Row untuk Tombol Aksi -->
                                        <div class="row mt-3">
                                            <div class="col">
                                                <div class="d-flex flex-wrap gap-2">
                                                    <!-- Tombol Filter -->
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fa fa-filter"></i> Filter
                                                    </button>
                                                    <!-- Tombol Reset -->
                                                    <a href="{{ route('report.product_reports') }}" class="btn btn-secondary">
                                                        <i class="fa fa-sync"></i> Reset
                                                    </a>

                                                    <a href="{{ route('report.product_reports.export', [
                                                            'start_date' => request('start_date', now()->format('Y-m-d')),
                                                            'end_date' => request('end_date', now()->format('Y-m-d')),
                                                            'status' => request('status'),
                                                            'customer_id' => request('customer_id'),
                                                            'product_id' => request('product_id')  // Tambahkan ini
                                                        ]) }}" class="btn btn-primary">
                                                        <i class="fa fa-file-excel"></i> Export Excel
                                                    </a>



                                                    <a href="{{ route('report.product_reports.export_pdf', [
                                                            'start_date' => request('start_date', now()->format('Y-m-d')),
                                                            'end_date' => request('end_date', now()->format('Y-m-d')),
                                                            'status' => request('status'),
                                                            'customer_id' => request('customer_id'),
                                                            'product_id' => request('product_id')  // Tambahkan ini
                                                        ]) }}" class="btn btn-danger">
                                                        <i class="fa fa-file-pdf"></i> Export PDF
                                                    </a>

                                                    <a href="{{ route('report.product_reports.preview_pdf', [
                                                            'start_date' => request('start_date', now()->format('Y-m-d')),
                                                            'end_date' => request('end_date', now()->format('Y-m-d')),
                                                            'status' => request('status'),
                                                            'customer_id' => request('customer_id'),
                                                            'product_id' => request('product_id')  // Tambahkan ini
                                                        ]) }}" class="btn btn-info" target="_blank">
                                                        <i class="fa fa-eye"></i> Preview PDF
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </form>



                                </div>
                            </div>


                            @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                            @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                            @endif



                            <table id="scroll_hor" class="table border table-striped table-bordered display nowrap" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>No Order</th>
                                        <th>Tanggal Order</th>
                                        <th>Customer Name</th>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Order Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $globalCounter = 1;
                                    $totalOrderPrice = 0;
                                    $totalQuantity = 0;
                                    $grandTotal = 0;
                                    @endphp
                                    @foreach ($data_orders as $order)
                                    @foreach ($order->orderItems as $orderItem)
                                    <tr>
                                        <td>{{ $globalCounter++ }}</td>
                                        <td>{{ $order->no_order }}</td>
                                        <td>{{ \Carbon\Carbon::parse($order->order_date)->format('d-m-Y') }}</td>
                                        <td>{{ $order->customer->name }}</td>
                                        <td>{{ $orderItem->product->name }}</td>
                                        <td style="text-align: right;">{{ $orderItem->quantity }}</td>
                                        <td class="text-end">{{ 'Rp ' . number_format($orderItem->order_price, 0, ',', '.') }}</td>
                                        @php
                                        $itemTotal = $orderItem->quantity * $orderItem->order_price;
                                        $totalOrderPrice += $orderItem->order_price;
                                        $totalQuantity += $orderItem->quantity;
                                        $grandTotal += $itemTotal;
                                        @endphp
                                        <td class="text-end">{{ 'Rp ' . number_format($itemTotal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5" class="text-center fw-bold">Total</td>
                                        <td class="text-end fw-bold">{{ $totalQuantity }}</td>
                                        <td class="text-end fw-bold">{{ 'Rp ' . number_format($totalOrderPrice, 0, ',', '.') }}</td>
                                        <td class="text-end fw-bold">{{ 'Rp ' . number_format($grandTotal, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
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
        $('#customer_id').select2({
            placeholder: "Pilih Pelanggan",
            allowClear: true,
            width: '100%'
        });

        $('#product_id').select2({
            placeholder: "Pilih Produk",
            allowClear: true,
            width: '100%'
        });
    });
</script>

@endpush