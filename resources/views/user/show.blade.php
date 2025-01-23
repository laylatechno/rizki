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
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{ route('users.index') }}">Halaman User</a></li>
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
                            <div class="mb-3">
                                <strong>Nama:</strong> {{ $data_user->name }}
                            </div>
                            <div class="mb-3">
                                <strong>Email:</strong> {{ $data_user->email }}
                            </div>
                            <div class="mb-3">
                                <strong>Roles:</strong> 
                                {{ $data_user->getRoleNames()->implode(', ') }}
                            </div>
                            <a class="btn btn-warning mb-2" href="{{ route('users.index') }}"><i class="fa fa-undo"></i> Kembali</a>
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
