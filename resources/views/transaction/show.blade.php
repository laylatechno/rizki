@extends('layouts.app')

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
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted text-decoration-none" href="{{ route('transactions.index') }}">Halaman Transaksi</a></li>
                            <li class="breadcrumb-item" aria-current="page">{{ $subtitle }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('template/back') }}/dist/images/breadcrumb/ChatBc.png" alt="" class="img-fluid mb-n4">
                    </div>
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
                            <div class="row">
                                <!-- Menampilkan Nama Transaksi -->
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                    <div class="form-item">
                                        <strong>Nama Transaksi:</strong>
                                        <p>{{ $data_transactions->name }}</p>
                                    </div>
                                </div>
                                
                                <!-- Menampilkan Tanggal Transaksi -->
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                    <div class="form-item">
                                        <strong>Tanggal:</strong>
                                        <p>{{ $data_transactions->transaction_date }}</p>
                                    </div>
                                </div>

                                <!-- Menampilkan Kategori Kas (dropdown) -->
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                    <div class="form-item">
                                        <strong>Kategori Kas:</strong>
                                        <p>{{ $data_cash->where('id', $data_transactions->cash_id)->first()->name ?? 'Tidak Ditemukan' }}</p>
                                    </div>
                                </div>

                                <!-- Menampilkan Kategori Transaksi -->
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                    <div class="form-item">
                                        <strong>Kategori Transaksi:</strong>
                                        <p>{{ $data_transaction_categories->where('id', $data_transactions->transaction_category_id)->first()->name ?? 'Tidak Ditemukan' }}</p>
                                    </div>
                                </div>

                                <!-- Menampilkan Jumlah Transaksi -->
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                    <div class="form-item">
                                        <strong>Jumlah:</strong>
                                        <p>{{ number_format($data_transactions->amount, 2) }}</p>
                                    </div>
                                </div>

                                <!-- Menampilkan Deskripsi (Jika ada) -->
                                @if($data_transactions->description)
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                    <div class="form-item">
                                        <strong>Deskripsi:</strong>
                                        <p>{{ $data_transactions->description }}</p>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Tombol Kembali ke Daftar Transaksi -->
                            <a class="btn btn-warning mb-2 mt-3" href="{{ route('transactions.index') }}"><i class="fa fa-undo"></i> Kembali</a>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

@endsection

@push('script')
@endpush
