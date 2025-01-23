<style>
    @media print {
        /* Sembunyikan elemen sidebar, navbar, dan footer */
        .sidebar, .navbar, .breadcrumb, .card.bg-light-info, .btn, footer {
            display: none;
        }

        /* Atur lebar kontainer untuk keperluan cetak */
        .container-fluid {
            width: 100%;
            padding: 0;
        }

        /* Mengurangi ukuran gambar */
        .card-body img {
            max-width: 5%; /* Atur ukuran gambar */
            margin-top: 10px;
        }

        /* Atur lebar tabel agar lebih rapih */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        /* Perbaiki ukuran font untuk cetakan */
        h3, h4 {
            font-size: 16px;
        }

        .breadcrumb {
            font-size: 12px;
        }
    }
</style>

<div class="container-fluid">
    <section class="datatables">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3 class="mb-3 mt-3">Detail Stock Opname</h3>

                        <div class="form-group mb-3">
                            <label for="opname_date">Tanggal Stock Opname :</label>
                            <label class="form-control">{{ $data_stock_opname->opname_date }}</label>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Deskripsi :</label>
                            <label class="form-control">{{ $data_stock_opname->description }}</label>
                        </div>

                        @if($data_stock_opname->image)
                            <div class="form-group mb-3">
                                <label for="image">Gambar :</label>
                                <br>
                                <img src="{{ asset('upload/stock_opname/'.$data_stock_opname->image) }}" alt="Gambar Stock Opname" style="max-width: 5%; margin-top: 10px;">
                            </div>
                        @endif

                        <h3 class="mb-3 mt-3">Produk yang Diperiksa</h3>
                        <table class="table border table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Nama Produk</th>
                                    <th>Stock Sistem</th>
                                    <th>Stock Real</th>
                                    <th>Selisih</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_products as $product)
                                    @php
                                        $detail = $data_stock_opname->stockOpnameDetails->firstWhere('product_id', $product->id);
                                    @endphp
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>{{ $detail ? $detail->physical_stock : '0' }}</td>
                                        <td>{{ $detail ? $detail->difference : '0' }}</td>
                                        <td>{{ $detail ? $detail->description_detail : '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    window.print();
</script>
