@extends('layouts.app')
@push('css')
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
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{ route('customers.index') }}">Halaman Pelanggan</a></li>
                            <li class="breadcrumb-item">{{ $subtitle }}</li>
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
                            @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> Ada beberapa masalah dengan data yang anda masukkan.<br><br>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif

                            <form method="POST" action="{{ route('customers.update', $data_customers->id) }}">
                                @csrf
                                @method('PUT')


                                <div class="row">
                                    <div class="form-group mb-3">
                                        <label for="name">Nama Pelanggan</label>
                                        <input type="text" name="name" class="form-control" id="name" value="{{ $data_customers->name }}" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="email">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $data_customers->email }}" id="email">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="phone">No Telp</label>
                                        <input type="number" name="phone" class="form-control" value="{{ $data_customers->phone }}" id="phone">
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="customer_category_id">Kategori Pelanggan</label>
                                        <select id="customer_category_id" name="customer_category_id" class="form-control" required>
                                            <option value="" disabled>--Pilih Kategori Pelanggan--</option>
                                            @foreach ($data_customer_categories as $p)
                                            <option value="{{ $p->id }}"
                                                {{ (isset($data_customers) && $data_customers->customer_category_id == $p->id) ? 'selected' : '' }}>
                                                {{ $p->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                                        <button type="submit" class="btn btn-primary btn-sm mb-3"><i class="fa fa-save"></i> Simpan</button>
                                        <a class="btn btn-warning btn-sm mb-3" href="{{ route('customers.index') }}"><i class="fa fa-undo"></i> Kembali</a>
                                    </div>
                                </div>



                            </form>
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