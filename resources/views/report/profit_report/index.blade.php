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

                                    <form method="GET" action="{{ route('report.profit_reports') }}" class="mb-4">
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
                                                <label for="cash_id" class="form-label">Semua Kas</label>
                                                <select name="cash_id" id="cash_id" class="form-control">
                                                    <option value="">Semua Kas</option>
                                                    @foreach($cashes as $cash)
                                                    <option value="{{ $cash->id }}" {{ request('cash_id') == $cash->id ? 'selected' : '' }}>
                                                        {{ $cash->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <!-- Filter Kategori Transaksi -->
                                            <div class="col-md-3">
                                                <label for="category" class="form-label">Kategori Transaksi</label>
                                                <select name="category" id="category" class="form-control">
                                                    <option value="">Semua Kategori</option>
                                                    <option value="tambah" {{ request('category') == 'tambah' ? 'selected' : '' }}>Tambah</option>
                                                    <option value="kurang" {{ request('category') == 'kurang' ? 'selected' : '' }}>Kurang</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col">
                                                <div class="d-flex flex-wrap gap-2">
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fa fa-filter"></i> Filter
                                                    </button>
                                                    <a href="{{ route('report.profit_reports') }}" class="btn btn-secondary">
                                                        <i class="fa fa-sync"></i> Reset
                                                    </a>
                                                    <a href="{{ route('report.profit_reports.export', [
                    'start_date' => request('start_date', now()->format('Y-m-d')),
                    'end_date' => request('end_date', now()->format('Y-m-d')),
                    'cash_id' => request('cash_id'),
                    'category' => request('category')
                ]) }}" class="btn btn-primary">
                                                        <i class="fa fa-file-excel"></i> Export Excel
                                                    </a>
                                                    <a href="{{ route('report.profit_reports.export_pdf', [
                    'start_date' => request()->start_date,
                    'end_date' => request()->end_date,
                    'cash_id' => request()->cash_id,
                    'category' => request()->category
                ]) }}" class="btn btn-danger">
                                                        <i class="fa fa-file-pdf"></i> Export PDF
                                                    </a>
                                                    <a href="{{ route('report.profit_reports.preview_pdf', [
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
                                                <th width="5%">Tanggal Transaksi</th>
                                                <th>Kas</th>
                                                <!-- <th>Kategori</th> -->
                                                <th>Transaksi</th>
                                                <th>Jumlah Tambah</th>
                                                <th>Jumlah Kurang</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $totalTambah = 0;
                                            $totalKurang = 0;
                                            @endphp
                                            @foreach($profitLoss as $index => $entry)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $entry->date }}</td>
                                                <td>{{ $entry->cash->name ?? 'N/A' }}</td>
                                                <!-- <td>{{ $entry->category ?? 'N/A' }}</td> -->
                                                <td>
                                                    @if($entry->transaction_id)
                                                    {{ $entry->transaction->name ?? 'N/A' }} <!-- Menampilkan nama transaksi -->
                                                    @elseif($entry->order_id)
                                                    Penjualan - {{ $entry->order->no_order }} - {{ $entry->order->description ?? 'N/A' }} <!-- Menampilkan 'Penjualan' dan deskripsi dari order -->
                                                    @elseif($entry->purchase_id)
                                                    Pembelian - {{ $entry->purchase->no_purchase }} - {{ $entry->purchase->description ?? 'N/A' }} <!-- Menampilkan 'Pembelian' dan deskripsi dari purchase -->
                                                    @else
                                                    'N/A' <!-- Jika tidak ada yang terisi -->
                                                    @endif
                                                </td>

                                                <td>
                                                    @if($entry->category === 'tambah')
                                                    @php
                                                    $totalTambah += $entry->amount;
                                                    @endphp
                                                    {{ number_format($entry->amount, 0, ',', '.') }}
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($entry->category === 'kurang')
                                                    @php
                                                    $totalKurang += $entry->amount;
                                                    @endphp
                                                    {{ number_format($entry->amount, 0, ',', '.') }}
                                                    @else
                                                    -
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end">Total:</th>
                                                <th class="text-success">{{ number_format($totalTambah, 0, ',', '.') }}</th>
                                                <th class="text-danger">{{ number_format($totalKurang, 0, ',', '.') }}</th>
                                            </tr>
                                            <tr>
                                                <th colspan="4" class="text-end">Selisih:</th>
                                                <th class="text-primary" colspan="2">
                                                    {{ number_format($totalTambah - $totalKurang, 0, ',', '.') }}
                                                </th>
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
        $('#cash_id').select2({
            placeholder: "Pilih Kas",
            allowClear: true,
            width: '100%'
        });

        $('#transaction_category_id').select2({
            placeholder: "Pilih Kategori",
            allowClear: true,
            width: '100%'
        });
    });
</script>

 
@endpush