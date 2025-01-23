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
                                <a class="text-muted text-decoration-none" href="{{ route('adjustments.index') }}">Halaman Adjustment</a>
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
                        <div class="row mb-3">
                            <label class="col-md-3 fw-bold">Nomor Adjustment</label>
                            <div class="col-md-9">
                                <p class="form-control-plaintext">{{ $adjustment->adjustment_number }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 fw-bold">Tanggal Adjustment</label>
                            <div class="col-md-9">
                                <p class="form-control-plaintext">{{ \Carbon\Carbon::parse($adjustment->adjustment_date)->format('d M Y') }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 fw-bold">Deskripsi</label>
                            <div class="col-md-9">
                                <p class="form-control-plaintext">{{ $adjustment->description ?? '-' }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 fw-bold">Total Adjustment</label>
                            <div class="col-md-9">
                                <p class="form-control-plaintext">{{ number_format($adjustment->total, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-3 fw-bold">Gambar Bukti</label>
                            <div class="col-md-9">
                                @if ($adjustment->image)
                                <img src="{{ asset('upload/adjustments/' . $adjustment->image) }}" alt="Adjustment Image" class="img-thumbnail" style="max-width: 200px;">
                                @else
                                <p class="form-control-plaintext">Tidak ada gambar</p>
                                @endif
                            </div>
                        </div>

                        <h4 class="mt-4">Detail Produk</h4>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Alasan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($adjustment->details as $index => $detail)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $detail->product->name }}</td>
                                    <td>{{ $detail->quantity }}</td>
                                    <td>{{ $detail->reason }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <a href="{{ route('adjustments.print', $adjustment->id) }}" class="btn btn-secondary">
                                <i class="fas fa-print"></i> Cetak
                            </a>


                            <a href="{{ route('adjustments.index') }}" class="btn btn-danger" style="color:white;">
                                <i class="fas fa-step-backward"></i> Kembali
                            </a>
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