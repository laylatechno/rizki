@extends('layouts.app')
@push('css')
<link rel="stylesheet" href="{{ asset('template/back') }}/dist/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css">
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

                            @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                            @endif

                            @can('loghistori-deleteall')

                            <!-- Ganti onClick untuk memanggil SweetAlert -->
                            <a href="javascript:void(0);"
                                class="btn btn-danger mb-3"
                                onclick="confirmDelete()">
                                <i class="fa fa-trash"></i> Hapus Semua Data
                            </a>
                            @endcan
                            <table id="scroll_hor"
                                class="table border table-striped table-bordered display nowrap"
                                style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tabel</th>
                                        <th>ID Entitas</th>
                                        <th>Aksi</th>
                                        <th>Waktu</th>
                                        <th>Pengguna</th>
                                        <th>Data Lama</th>
                                        <th>Data Baru</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($data_log_histori as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $p->Tabel_Asal }}</td>
                                        <td>{{ $p->ID_Entitas }}</td>
                                        <td>{{ $p->Aksi }}</td>
                                        <td>{{ $p->Waktu }}</td>
                                        <td>{{ $p->Waktu }}</td>
                                        {{-- <td>{{ $p->user->name }}</td> --}}
                                        <td>{{ $p->Data_Lama }}</td>
                                        <td>{{ $p->Data_Baru }}</td>


                                    </tr>
                                    @endforeach





                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

 
@push('script')
    <!-- Memuat SweetAlert2 -->
    

    <script>
        function confirmDelete() {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Ganti URL penghapusan semua data sesuai dengan route Anda
                    window.location.href = "{{ route('log-histori.delete-all') }}";
                }
            });
        }
    </script>


<script src="{{ asset('template/back') }}/dist/libs/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/back') }}/dist/js/datatable/datatable-basic.init.js"></script>
@endpush