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
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted text-decoration-none" href="{{ route('products.index') }}">Daftar Produk</a></li>
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
                        <div class="row">
                            <!-- Nama Produk -->
                            <div class="row">

                                <div class="col-md-6 mb-3">
                                    <div class="form-item">
                                        <strong>Nama Produk:</strong>
                                        <p>{{ $data_product->name }}</p>
                                    </div>
                                </div>

                                <!-- Kategori Produk -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-item">
                                        <strong>Kategori Produk:</strong>
                                        <p>{{ $data_product->category->name ?? 'Tidak ada kategori' }}</p>
                                    </div>
                                </div>
                            </div>


                            <!-- Satuan Produk -->
                            <div class="col-md-12 mb-3">
                                <div class="form-item">
                                    <strong>Satuan Produk:</strong>
                                    <p>{{ $data_product->unit->name ?? 'Tidak ada satuan' }}</p>
                                </div>
                            </div>

                            <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-item">
                                    <strong>Stok:</strong>
                                    <p>{{ $data_product->stock ?? 'Tidak ada stock' }}</p>
                                </div>
                            </div>


                            <div class="col-md-6 mb-3">
                                <div class="form-item">
                                    <strong>Reminder Stok:</strong>
                                    <p>{{ $data_product->reminder ?? 'Tidak ada reminder' }}</p>
                                </div>
                            </div>
                            </div>
                          
                            <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-item">
                                    <strong>Harga:</strong>
                                    <p>Rp. {{ number_format($data_product->price, 0, ',', '.') ?? 'Tidak ada harga' }}</p>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="form-item">
                                    <strong>Harga Jual:</strong>
                                    <p>Rp. {{ number_format($data_product->cost_price, 0, ',', '.') ?? 'Tidak ada harga jual' }}</p>
                                </div>
                            </div>

                        </div>
                           


                            <!-- Daftar Harga Produk -->
                            <div class="col-md-12 mb-3">
                                <div class="form-item">
                                    <h4>Daftar Harga Berdasarkan Kategori Konsumen</h4>
                                    @if($data_product->productPrices->isNotEmpty())
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Kategori Konsumen</th>
                                                <th>Harga</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($data_product->productPrices as $price)
                                            <tr>
                                                <td>{{ $price->customerCategory->name ?? 'Tidak Ada Kategori' }}</td>
                                                <td>Rp {{ number_format($price->price, 0, ',', '.') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    @else
                                    <p>Tidak ada harga tersedia untuk produk ini.</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Deskripsi Produk -->
                            <div class="col-md-12 mb-3">
                                <div class="form-item">
                                    <strong>Deskripsi:</strong>
                                    <p>{{ $data_product->description ?? 'Tidak ada deskripsi tersedia' }}</p>
                                </div>
                            </div>

                            <!-- Gambar Produk -->
                            <div class="col-md-12 mb-3">
                                <div class="form-item">
                                    <strong>Gambar:</strong>
                                    <br>
                                    @if($data_product->image)
                                    <a href="{{ asset('upload/products/' . $data_product->image) }}" target="_blank">
                                        <img src="{{ asset('upload/products/' . $data_product->image) }}" alt="Product Image" style="max-width: 200px; max-height: 200px;">
                                    </a>
                                    @else
                                    <p>Tidak ada gambar tersedia</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Tombol Kembali -->
                        <a href="{{ route('products.index') }}" class="btn btn-warning mt-3">
                            <i class="fa fa-undo"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection