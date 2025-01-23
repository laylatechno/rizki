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
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{ route('transaction_categories.index') }}">Halaman Kategori Transaksi</a></li>
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

                            <form method="POST" action="{{ route('transaction_categories.update', $data_transaction_categories->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group mb-3">
                                    <label for="name">Nama Kategori Produk</label>
                                    <span class="text-danger">*</span>
                                    <input type="text" name="name" class="form-control" id="name" value="{{ $data_transaction_categories->name }}" required>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="parent_type">Type</label>
                                    <span class="text-danger">*</span>
                                    <select name="parent_type" class="form-control" id="parent_type" required>
                                        <option value="">--Pilih Tipe--</option>
                                        <option value="tambah" {{ old('parent_type', $data_transaction_categories->parent_type) == 'tambah' ? 'selected' : '' }}>Tambah</option>
                                        <option value="kurang" {{ old('parent_type', $data_transaction_categories->parent_type) == 'kurang' ? 'selected' : '' }}>Kurang</option>
                                    </select>
                                </div>


                                <div class="form-group mb-3">
                                    <label for="description">Deskripsi</label>
                                    <textarea class="form-control" name="description" id="description">{{ $data_transaction_categories->description }}</textarea>
                                </div>


                                <!-- Buttons -->
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-primary btn-sm mb-3"><i class="fa fa-save"></i> Update</button>
                                    <a class="btn btn-warning btn-sm mb-3" href="{{ route('transaction_categories.index') }}"><i class="fa fa-undo"></i> Kembali</a>
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