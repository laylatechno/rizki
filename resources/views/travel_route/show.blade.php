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
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted text-decoration-none" href="{{ route('travel_routes.index') }}">Halaman Travel Route</a></li>
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
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                    <div class="form-item">
                                        <strong>Nama Travel Route:</strong>
                                        {{ $data_travel_routes->name }}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                    <div class="form-item">
                                        <strong>Rute Awal:</strong>
                                        {{ $data_travel_routes->start }}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                    <div class="form-item">
                                        <strong>Rute Akhir:</strong>
                                        {{ $data_travel_routes->end }}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                    <div class="form-item">
                                        <strong>Harga:</strong>
                                        {{ $data_travel_routes->price }}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                    <div class="form-item">
                                        <strong>Deskripsi:</strong>
                                        {!! $data_travel_routes->description !!}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                    <div class="form-item">
                                        <strong>Urutan:</strong>
                                        {{ $data_travel_routes->position }}
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-12 mb-3">
                                    <div class="form-item">
                                        <strong>Gambar:</strong>
                                        <br>
                                        @if ($data_travel_routes->image)
                                        <img src="{{ asset('upload/travel_routes/' . $data_travel_routes->image) }}" alt="Adjustment Image" class="img-thumbnail" style="max-width: 200px;">
                                        @else
                                        <p class="form-control-plaintext">Tidak ada gambar</p>
                                        @endif
                                    </div>
                                </div>


                            </div>

                            <a class="btn btn-warning mb-2 mt-3" href="{{ route('travel_routes.index') }}"><i class="fa fa-undo"></i> Kembali</a>

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