@extends('layouts.app')
 

@section('content')
<div class="container-fluid">

    <!-- Breadcrumb Section -->
    <div class="card bg-light-info shadow-none position-relative overflow-hidden" style="border: solid 0.5px #ccc;">
        <div class="card-body px-4 py-3">
            <div class="row align-items-center">
                <div class="col-9">
                    <h4 class="fw-semibold mb-8">{{ $title }}</h4>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="/">Beranda</a></li>
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted text-decoration-none" href="{{ route('orders.index') }}">Daftar Produk</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $subtitle }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3">
                    <div class="text-center mb-n5">
                        <img src="{{ asset('template/back') }}/dist/images/breadcrumb/ChatBc.png" alt="Breadcrumb Icon" class="img-fluid mb-n4">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Details Section -->
    <section class="product-details mt-3">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Informasi Penjualan</h4>
                        <table class="table table-bordered print-section">
                            <tr>
                                <th>Status</th>
                                <td>
                                    @if ($order->status == 'Lunas')
                                    <span class="badge bg-success">Lunas</span>
                                    @elseif ($order->status == 'Pending')
                                    <span class="badge bg-warning">Pending</span>
                                    @elseif ($order->status == 'Pesanan Penjualan')
                                    <span class="badge bg-secondary">Pesanan Penjualan</span>
                                    @else
                                    <span class="badge bg-danger">Belum Lunas</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tanggal Penjualan</th>
                                <td>{{ $order->created_at }}</td>
                            </tr>
                            <tr>
                                <th>Total Biaya</th>
                                <td>{{ number_format($order->total_cost, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Pelanggan</th>
                                <td>{{ $order->customer ? $order->customer->name : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Kas Pembayaran</th>
                                <td>{{ $order->cash ? $order->cash->name : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Pengguna</th>
                                <td>{{ $order->user ? $order->user->name : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Pembayaran</th>
                                <td>
                                    @if ($order->type_payment == 'CASH')
                                    <span class="badge bg-success">CASH</span>
                                    @else
                                    <span class="badge bg-warning">TRANSFER</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>{{ $order->description }}</td>
                            </tr>
                        </table>

                        <h4>Detail Produk</h4>
                        <table class="table table-bordered print-section">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Harga Beli</th>
                                    <th>Jumlah</th>
                                    <th>Total Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->product->name }}</td>
                                    <td>Rp {{ number_format($item->order_price, 0, ',', '.') }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('orders.index') }}" class="btn btn-danger">
                                <i class="fas fa-step-backward"></i> Kembali
                            </a>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
