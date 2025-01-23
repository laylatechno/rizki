@extends('layouts.app')

@push('css')
<link rel="stylesheet" href="{{ asset('template/back/dist/libs/select2/dist/css/select2.min.css') }}">
@endpush

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
                            <li class="breadcrumb-item"><a class="text-muted text-decoration-none" href="{{ route('users.index') }}">Halaman User</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $subtitle }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-3 text-center">
                    <img src="{{ asset('template/back/dist/images/breadcrumb/ChatBc.png') }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <section class="datatables">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> Ada beberapa masalah dengan data yang Anda masukkan.
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label"><strong>Nama:</strong></label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Nama" value="{{ old('name', $user->name ?? '') }}">
                                </div>

                                <!-- Input Email -->
                                <div class="col-md-12 mb-3">
                                    <label for="email" class="form-label"><strong>Email:</strong></label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email" value="{{ old('email', $user->email ?? '') }}">
                                </div>

                                <!-- Input Password -->
                                <div class="col-md-12 mb-3">
                                    <label for="password" class="form-label"><strong>Password:</strong></label>
                                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                                </div>

                                <!-- Input Konfirmasi Password -->
                                <div class="col-md-12 mb-3">
                                    <label for="confirm-password" class="form-label"><strong>Konfirmasi Password:</strong></label>
                                    <input type="password" name="confirm-password" id="confirm-password" class="form-control" placeholder="Konfirmasi Password">
                                </div>

                                <!-- Select Role -->
                                <div class="col-md-12 mb-3">
                                    <label for="roles" class="form-label"><strong>Role:</strong></label>
                                    <select name="roles[]" id="roles" class="select2 form-control" multiple="multiple" style="width: 100%">
                                        <option></option>
                                        @foreach ($roles as $value => $label)
                                        <option value="{{ $value }}" {{ in_array($value, old('roles', $userRoles ?? [])) ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="col-md-12 mb-3">
                                    <label for="roles" class="form-label"><strong>Gambar:</strong></label>
                                    <input type="file" name="image" class="form-control" id="image" onchange="previewImage()">
                                    <canvas id="preview_canvas" style="display: none; max-width: 100%; margin-top: 10px;"></canvas>
                                    <img id="preview_image" src="#" alt="Preview Logo" style="display: none; max-width: 100%; margin-top: 10px;">


                              
                                </div>

                                <!-- Button Actions -->
                                <div class="col-md-12 mt-3">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-save"></i> Simpan</button>
                                    <a href="{{ route('users.index') }}" class="btn btn-warning btn-sm"><i class="fa fa-undo"></i> Kembali</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('script')
<script src="{{ asset('template/back/dist/libs/select2/dist/js/select2.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "--Pilih Role--",
            allowClear: true
        });
    });
</script>
@endpush