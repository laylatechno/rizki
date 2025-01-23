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

                                    <form action="{{ route('report.order_reports') }}" method="GET" class="mb-4">
                                        <!-- Row untuk Inputan -->
                                        <div class="row">
                                            <!-- Kolom Tanggal Awal -->
                                            <div class="col-md-3 mt-3">
                                                <label for="start_date" class="form-label">Tanggal Awal</label>
                                                <input type="date" name="start_date" id="start_date" class="form-control"
                                                    value="{{ request('start_date', now()->format('Y-m-d')) }}">
                                            </div>

                                            <!-- Kolom Tanggal Akhir -->
                                            <div class="col-md-3 mt-3">
                                                <label for="end_date" class="form-label">Tanggal Akhir</label>
                                                <input type="date" name="end_date" id="end_date" class="form-control"
                                                    value="{{ request('end_date', now()->format('Y-m-d')) }}">
                                            </div>


                                            <!-- Kolom Filter Status -->
                                            <div class="col-md-3 mt-3">
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
                                            <div class="col-md-3 mt-3">
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


                                            <!-- Kolom Filter Metode Pembayaran -->
                                            <div class="col-md-3 mt-3">
                                                <label for="type_payment" class="form-label">Metode Pembayaran</label>
                                                <select name="type_payment" id="type_payment" class="form-select">
                                                    <option value="">Semua Metode Pembayaran</option>
                                                    <option value="CASH" {{ request('type_payment') == 'CASH' ? 'selected' : '' }}>CASH</option>
                                                    <option value="TRANSFER" {{ request('type_payment') == 'TRANSFER' ? 'selected' : '' }}>TRANSFER</option>
                                                    <!-- Add other payment types if needed -->
                                                </select>
                                            </div>

                                            <!-- Kolom Filter Cash/Bank -->
                                            <div class="col-md-3 mt-3">
                                                <label for="cash_id" class="form-label">Kas/Bank</label>
                                                <select name="cash_id[]" id="cash_id" class="form-select" multiple>
                                                    @foreach ($cashes as $cash)
                                                    <option value="{{ $cash->id }}"
                                                        {{ (is_array(request('cash_id')) && in_array($cash->id, request('cash_id'))) ? 'selected' : '' }}>
                                                        {{ $cash->name }}
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
                                                    <a href="{{ route('report.order_reports') }}" class="btn btn-secondary">
                                                        <i class="fa fa-sync"></i> Reset
                                                    </a>

                                                    <!-- Tombol Export Excel -->
                                                    <a href="{{ route('report.order_reports.export', [
                                                        'start_date' => request('start_date', now()->format('Y-m-d')),
                                                        'end_date' => request('end_date', now()->format('Y-m-d')),
                                                        'status' => request('status'),
                                                        'customer_id' => request('customer_id'),
                                                        'type_payment' => request('type_payment'),
                                                        'cash_id' => request('cash_id')
                                                    ]) }}" class="btn btn-primary">
                                                        <i class="fa fa-file-excel"></i> Export Excel
                                                    </a>

                                                    <!-- Tombol Export PDF -->
                                                    <a href="{{ route('report.order_reports.export_pdf', [
                                                            'start_date' => request('start_date', now()->format('Y-m-d')),
                                                            'end_date' => request('end_date', now()->format('Y-m-d')),
                                                            'status' => request('status'),
                                                            'customer_id' => request('customer_id'),
                                                             'type_payment' => request('type_payment'),
                                                        'cash_id' => request('cash_id')
                                                        ]) }}" class="btn btn-danger">
                                                        <i class="fa fa-file-pdf"></i> Export PDF
                                                    </a>

                                                    <!-- Tombol Preview PDF -->
                                                    <a href="{{ route('report.order_reports.preview_pdf', [
                                                            'start_date' => request('start_date', now()->format('Y-m-d')),
                                                            'end_date' => request('end_date', now()->format('Y-m-d')),
                                                            'status' => request('status'),
                                                            'customer_id' => request('customer_id'),
                                                             'type_payment' => request('type_payment'),
                                                        'cash_id' => request('cash_id')
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
                                        <th width="5px">No</th>
                                        <th>No Penjualan</th>
                                        <th>Tanggal Penjualan</th>
                                        <th>Pelanggan</th>
                                        <th>Pengguna</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Kas/Bank</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data_orders as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $p->no_order }}</td>

                                        <!-- Tanggal Penjualan dengan Hari -->
                                        <td>
                                            {{ \Carbon\Carbon::parse($p->order_date)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                        </td>

                                        <td>{{ $p->customer->name ?? 'No Data' }}</td>

                                        <td>{{ $p->user->name }}</td>
                                        <td>Rp {{ number_format($p->total_cost, 0, ',', '.') }}</td>

                                        <!-- Status dengan Badge -->
                                        <td>
                                            @if ($p->status == 'Lunas')
                                            <span class="badge bg-success">Lunas</span>
                                            @elseif ($p->status == 'Pending')
                                            <span class="badge bg-warning">Pending</span>
                                            @elseif ($p->status == 'Pesanan Penjualan')
                                            <span class="badge bg-primary">Pesanan Penjualan</span>
                                            @else
                                            <span class="badge bg-danger">Belum Lunas</span>
                                            @endif
                                        </td>

                                        <!-- In the table row -->
                                        <td>{{ $p->type_payment }}</td>
                                        <td>{{ $p->cash->name ?? 'No Data' }}</td>

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
        $('#customer_id').select2({
            placeholder: "Pilih Pelanggan",
            allowClear: true,
            width: '100%'
        });

        $('#cash_id').select2({
            placeholder: "Pilih Kas",
            allowClear: true,
            width: '100%'
        });
    });
</script>

@endpush