@extends('layouts.app')


@push('css')
<link rel="stylesheet" href="{{ asset('template/back') }}/dist/libs/select2/dist/css/select2.min.css">
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
                            <li class="breadcrumb-item" aria-current="page"><a class="text-muted text-decoration-none" href="{{ route('menu_groups.index') }}">Halaman Menu Group</a></li>
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

                            <form method="POST" action="{{ route('menu_groups.store') }}">
                                @csrf
                                <div class="row">
                                    <div class="form-group mb-3">
                                        <label for="name">Nama Menu Group</label>
                                        <input type="text" name="name" class="form-control" id="name" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="permission_name">Permission</label>
                                        <select
                                                name="permission_name"
                                                class="select2 form-control"
                                                style="height: 36px; width: 100%"
                                                placeholder="--Pilih Permission--">

                                                <option></option> <!-- Placeholder option for select2 -->
                                                <optgroup label="--Pilih Permission--">
                                                    @foreach ($data_permission as $value => $label)
                                                    <option value="{{ $value }}" {{ in_array($value, old('permission_name', [])) ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </optgroup>
                                            </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="status">Status</label>
                                        <select name="status" id="status"  class="form-control">
                                            <option value="Aktif">Aktif</option>
                                            <option value="Non Aktif">Non Aktif</option>
                                        </select>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="position">Urutan</label>
                                        <input type="number" name="position" class="form-control" id="position" required>
                                    </div>

                                    <div class="col-xs-12 col-sm-12 col-md-12 mt-3">
                                        <button type="submit" class="btn btn-primary btn-sm mb-3"><i class="fa fa-save"></i> Simpan</button>
                                        <a class="btn btn-warning btn-sm mb-3" href="{{ route('menu_groups.index') }}"><i class="fa fa-undo"></i> Kembali</a>
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
<script src="{{ asset('template/back') }}/dist/libs/select2/dist/js/select2.full.min.js"></script>
<script src="{{ asset('template/back') }}/dist/libs/select2/dist/js/select2.min.js"></script>
<script src="{{ asset('template/back') }}/dist/js/forms/select2.init.js"></script>
@endpush