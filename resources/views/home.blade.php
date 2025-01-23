@extends('layouts.app')

@section('content')
@push('css')
<!-- <link rel="stylesheet" href="{{ asset('template/back') }}/dist/css/style.min.css" /> -->
<link rel="stylesheet" href="{{ asset('template/back') }}/dist/libs/owl.carousel/dist/assets/owl.carousel.min.css">

@endpush
<div class="container-fluid">
    <!--  Owl carousel -->
    <div class="owl-carousel counter-carousel owl-theme">
        <div class="item">
            <a href="/pengguna" class="text-decoration-none">
                <div class="card border-0 zoom-in shadow-sm" style="background-color: #d1e7dd; border-radius: 10px;">
                    <div class="card-body">
                        <div class="text-center d-flex flex-column align-items-center">
                            <img src="{{ asset('template/back') }}/dist/images/icon/user.png"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;"
                                class="mb-3" alt="User Icon" />
                            <p class="fw-semibold fs-3 text-primary mb-1">Pengguna</p>
                            <h5 class="fw-semibold text-primary mb-0">{{ $totalPengguna }}</h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>


        <div class="item">
            <a href="/produk" class="text-decoration-none">
                <div class="card border-0 zoom-in shadow-sm" style="background-color: #d1e7dd; border-radius: 10px;">
                    <div class="card-body">
                        <div class="text-center d-flex flex-column align-items-center">
                            <img src="{{ asset('template/back') }}/dist/images/icon/product.png"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;"
                                class="mb-3" alt="Product Icon" />
                            <p class="fw-semibold fs-3 text-primary mb-1">Produk</p>
                            <h5 class="fw-semibold text-primary mb-0">{{ $totalProduk }}</h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>


        <div class="item">
            <a href="/pelanggan" class="text-decoration-none">
                <div class="card border-0 zoom-in shadow-sm" style="background-color: #d1e7dd; border-radius: 10px;">
                    <div class="card-body">
                        <div class="text-center d-flex flex-column align-items-center">
                            <img src="{{ asset('template/back') }}/dist/images/icon/customer.png"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;"
                                class="mb-3" alt="Customer Icon" />
                            <p class="fw-semibold fs-3 text-primary mb-1">Pelanggan</p>
                            <h5 class="fw-semibold text-primary mb-0">{{ $totalPelanggan }}</h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>


        <div class="item">
            <a href="/pelanggan" class="text-decoration-none">
                <div class="card border-0 zoom-in shadow-sm" style="background-color: #d1e7dd; border-radius: 10px;">
                    <div class="card-body">
                        <div class="text-center d-flex flex-column align-items-center">
                            <img src="{{ asset('template/back') }}/dist/images/icon/supplier.png"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;"
                                class="mb-3" alt="Customer Icon" />
                            <p class="fw-semibold fs-3 text-primary mb-1">Supplier</p>
                            <h5 class="fw-semibold text-primary mb-0">{{ $totalSupplier }}</h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        
        <div class="item">
            <a href="/pelanggan" class="text-decoration-none">
                <div class="card border-0 zoom-in shadow-sm" style="background-color: #d1e7dd; border-radius: 10px;">
                    <div class="card-body">
                        <div class="text-center d-flex flex-column align-items-center">
                            <img src="{{ asset('template/back') }}/dist/images/icon/order.png"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;"
                                class="mb-3" alt="Customer Icon" />
                            <p class="fw-semibold fs-3 text-primary mb-1">Penjualan</p>
                            <h5 class="fw-semibold text-primary mb-0">{{ $totalOrdersLunas }}</h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="item">
            <a href="/pelanggan" class="text-decoration-none">
                <div class="card border-0 zoom-in shadow-sm" style="background-color: #d1e7dd; border-radius: 10px;">
                    <div class="card-body">
                        <div class="text-center d-flex flex-column align-items-center">
                            <img src="{{ asset('template/back') }}/dist/images/icon/purchase.png"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;"
                                class="mb-3" alt="Customer Icon" />
                            <p class="fw-semibold fs-3 text-primary mb-1">Pembelian</p>
                            <h5 class="fw-semibold text-primary mb-0">{{ $totalPurchasesLunas }}</h5>
                        </div>
                    </div>
                </div>
            </a>
        </div>





    </div>
    <!--  Row 1 -->
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body">
                <h5 class="card-title text-center mb-4">Grafik Pembelian dan Pemesanan Tahun {{ $currentYear }}</h5>

                <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">

                    <div class="mb-3 mb-sm-0">
                        <form action="{{ route('home') }}" method="GET">
                            <div class="row">
                                <!-- Radio Button untuk memilih Filter: Tahun, Bulan, atau Tanggal -->
                                <div class="col-12 mb-3">


                                    <label class="form-label">Filter Transaksi Terbaru Berdasarkan</label>
                                    <div>
                                        <input type="radio" id="filter_month" name="filter_type" value="month" onchange="toggleFilterOptions('month')" {{ request('filter_type') == 'month' ? 'checked' : '' }}>
                                        <label for="filter_month">Bulan</label>

                                        <input type="radio" id="filter_date" name="filter_type" value="date" onchange="toggleFilterOptions('date')" {{ request('filter_type') == 'date' ? 'checked' : '' }}>
                                        <label for="filter_date">Tanggal</label>
                                    </div>
                                </div>

                                <!-- Filter berdasarkan Bulan -->
                                <div class="col-6 mb-3" id="month_filter" style="display: {{ request('filter_type') == 'month' ? 'block' : 'none' }}">
                                    <label for="month_select" class="form-label">Pilih Bulan</label>
                                    <select class="form-select" name="month" id="month_select">
                                        <option value="1" {{ request('month') == '1' ? 'selected' : '' }}>Januari</option>
                                        <option value="2" {{ request('month') == '2' ? 'selected' : '' }}>Februari</option>
                                        <option value="3" {{ request('month') == '3' ? 'selected' : '' }}>Maret</option>
                                        <option value="4" {{ request('month') == '4' ? 'selected' : '' }}>April</option>
                                        <option value="5" {{ request('month') == '5' ? 'selected' : '' }}>Mei</option>
                                        <option value="6" {{ request('month') == '6' ? 'selected' : '' }}>Juni</option>
                                        <option value="7" {{ request('month') == '7' ? 'selected' : '' }}>Juli</option>
                                        <option value="8" {{ request('month') == '8' ? 'selected' : '' }}>Agustus</option>
                                        <option value="9" {{ request('month') == '9' ? 'selected' : '' }}>September</option>
                                        <option value="10" {{ request('month') == '10' ? 'selected' : '' }}>Oktober</option>
                                        <option value="11" {{ request('month') == '11' ? 'selected' : '' }}>November</option>
                                        <option value="12" {{ request('month') == '12' ? 'selected' : '' }}>Desember</option>
                                    </select>
                                </div>


                                <!-- Filter berdasarkan Tanggal -->
                                <div class="col-3 mb-3" id="date_filter_start" style="display: {{ request('filter_type') == 'date' ? 'block' : 'none' }}">
                                    <label for="start_date" class="form-label">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}">
                                </div>
                                <div class="col-3 mb-3" id="date_filter_end" style="display: {{ request('filter_type') == 'date' ? 'block' : 'none' }}">
                                    <label for="end_date" class="form-label">Tanggal Akhir</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}">
                                </div>


                                <!-- Tombol Filter -->
                                <div class="col-6 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Grafik -->
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <canvas id="lineChart1"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <canvas id="lineChart2"></canvas>
                            </div>
                        </div>
                    </div>


                </div>


            </div>
        </div>
    </div>
    <!-- Row 1 -->
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body">

                <!-- Judul Grafik -->
                <!-- Grafik Penjualan Pertahun -->
                <h5 class="card-title text-center mb-4">Grafik Penjualan Pertahun {{ $currentYear }}</h5>


                <!-- Grafik -->
                <div class="row align-items-center">
                    <div class="col-lg-8 col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <canvas id="lineChart3"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <canvas id="lineChart4"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!-- Row 1 -->
    <div class="col-lg-12 d-flex align-items-stretch">
        <div class="card w-100">
            <div class="card-body">

                <!-- Grafik -->
                <div class="row align-items-center">
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-center mb-4">Grafik Transaksi Berdasarkan Kategori {{ $currentYear }}</h5>
                                <canvas id="horizontalBarChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title text-center mb-4">Grafik Transaksi Berdasarkan Kas {{ $currentYear }}</h5>
                                <canvas id="horizontalBarChartKas"></canvas>
                            </div>
                        </div>
                    </div>



                </div>

            </div>
        </div>
    </div>




    <!--  Row 2 -->
    <div class="row">


        <div class="col-lg-4 d-flex align-items-strech">
            <div class="card w-100">
                <div class="card-body">
                    <div>
                        <h5 class="card-title fw-semibold mb-1">Pembelian Bulanan per Kas</h5>
                        <p class="card-subtitle mb-0">Berdasarkan Kas</p>
                        <canvas id="monthlyPurchaseChart"></canvas> <!-- Canvas untuk grafik -->
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-light-primary rounded me-8 p-8 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-grid-dots text-primary fs-6"></i>
                                </div>
                                <div>
                                    <p class="fs-3 mb-0 fw-normal">Total Pembelian Bulan Ini</p>
                                    <h6 class="fw-semibold text-dark fs-4 mb-0">Rp. {{ number_format($totalMonthlyPurchases, 0, ',', '.') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>






        <div class="col-lg-4 d-flex align-items-strech">
            <div class="card w-100">
                <div class="card-body">
                    <div>
                        <h5 class="card-title fw-semibold mb-1">Penjualan Bulanan per Kas</h5>
                        <p class="card-subtitle mb-0">Berdasarkan Kas</p>
                        <canvas id="monthlyOrderChart"></canvas> <!-- Canvas untuk grafik -->
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-light-primary rounded me-8 p-8 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-grid-dots text-primary fs-6"></i>
                                </div>
                                <div>
                                    <p class="fs-3 mb-0 fw-normal">Total Penjualan Bulan Ini</p>
                                    <h6 class="fw-semibold text-dark fs-4 mb-0">Rp. {{ number_format($totalMonthlyOrders, 0, ',', '.') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <div class="col-lg-4 d-flex align-items-strech">
            <div class="card bg-primary border-0 w-100">
                <div class="card-body pb-0">
                    <h5 class="fw-semibold mb-1 text-white card-title">Produk Terlaris</h5>
                    <p class="fs-3 mb-1 text-white">Tahun {{ $currentYear }}</p>
                    <div class="text-center mt-0">
                        <img src="{{ asset('template/back') }}/dist/images/backgrounds/piggy.png" class="img-fluid" alt="" />
                    </div>
                </div>
                <div class="card mx-2 mb-2 mt-n2">
                    <div class="card-body">
                        @foreach($bestSellingProducts as $index => $product)
                        <div class="mb-{{ $loop->last ? '0' : '3' }} pb-{{ $loop->last ? '0' : '1' }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <h6 class="mb-1 fs-4 fw-semibold">{{ $product->name }}</h6>
                                    <p class="fs-3 mb-0">{{ number_format($product->total_quantity) }} Unit</p>
                                </div>
                                <div>
                                    <span class="badge {{ $index < 2 ? 'bg-light-primary text-primary' : 'bg-light-secondary text-secondary' }} fw-semibold fs-3">
                                        {{ $product->percentage }}%
                                    </span>
                                </div>
                            </div>
                            <div class="progress {{ $index < 2 ? 'bg-light-primary' : 'bg-light-secondary' }}" style="height: 4px;">
                                <div class="progress-bar {{ $index >= 2 ? 'bg-secondary' : '' }}"
                                    style="width: {{ $product->percentage }}%"
                                    role="progressbar"
                                    aria-valuenow="{{ $product->percentage }}"
                                    aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


    </div>


    <!--  Row 3 -->
    <div class="row">
        <div class="col-lg-4 d-flex align-items-strech">
            <div class="card w-100">
                <div class="card-body">
                    <h5 class="card-title fw-semibold">Grafik Penjualan</h5>
                    <p class="card-subtitle mb-0">Berdasarkan Pelanggan</p>
                    <canvas id="monthlyOrderCustomer"></canvas>
                    <div class="position-relative">
                        @foreach($topCustomers as $index => $customer)
                        @php
                        $colors = [
                        ['bg' => 'bg-light-primary', 'text' => 'text-primary'],
                        ['bg' => 'bg-light-success', 'text' => 'text-success'],
                        ['bg' => 'bg-light-danger', 'text' => 'text-danger']
                        ];
                        @endphp
                        <div class="d-flex align-items-center justify-content-between {{ !$loop->last ? 'mb-7' : '' }}">
                            <div class="d-flex">
                                <div class="p-6 {{ $colors[$index]['bg'] }} rounded me-6 d-flex align-items-center justify-content-center">
                                    <i class="ti ti-grid-dots {{ $colors[$index]['text'] }} fs-6"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fs-4 fw-semibold">{{ $loop->iteration }}. Top Customer</h6>
                                    <p class="fs-3 mb-0">{{ $customer->name }}</p>
                                </div>
                            </div>
                            <div class="{{ $colors[$index]['bg'] }} badge">
                                <p class="fs-3 {{ $colors[$index]['text'] }} fw-semibold mb-0">{{ $customer->total_orders }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>



        <div class="col-lg-8 d-flex align-items-strech">
            <div class="card w-100">
                <div class="card-body">
                    <div class="d-sm-flex d-block align-items-center justify-content-between mb-7">
                        <div class="mb-3 mb-sm-0">
                            <h5 class="card-title fw-semibold">Produk Terlaris</h5>
                            <p class="card-subtitle mb-0">Pada Bulan {{ $currentMonthName }}</p>
                        </div>
                        
                    </div>
                    <div class="table-responsive">
                        <table class="table align-middle text-nowrap mb-0">
                            <thead>
                                <tr class="text-muted fw-semibold">
                                    <th scope="col">Produk</th>
                                    <th scope="col">Kuantiti Terjual</th>
                                    <th scope="col">Pendapatan</th>
                                    <th scope="col">Persentase Penjualan</th>
                                </tr>
                            </thead>
                            <tbody class="border-top">
                                @foreach ($topSellingProducts as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-2 pe-1">
                                                <!-- Menampilkan gambar produk jika ada -->
                                                @if ($product->image)
                                                <img src="{{ asset('upload/products/' . $product->image) }}" class="rounded-circle" width="40" height="40" alt="Product Image" />
                                                @else
                                                <!-- Gambar default jika tidak ada gambar produk -->
                                                <img src="{{ asset('upload/products/default.png') }}" class="rounded-circle" width="40" height="40" alt="Default Image" />
                                                @endif
                                            </div>

                                            <div>
                                                <h6 class="fw-semibold mb-1">{{ $product->product_name }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="fs-3 text-dark mb-0">{{ $product->total_sold }}</p>
                                    </td>
                                    <td>
                                        <p class="fs-3 text-dark mb-0">Rp. {{ number_format($product->total_revenue, 0, ',', '.') }}</p>
                                    </td>
                                    <td>
                                        <p class="fs-3 text-dark mb-0">{{ $product->sales_percentage }}%</p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

@endsection

@push('script')
<!-- <script src="{{ asset('template/back') }}/dist/libs/jquery/dist/jquery.min.js"></script>
<script src="{{ asset('template/back') }}/dist/libs/simplebar/dist/simplebar.min.js"></script>
<script src="{{ asset('template/back') }}/dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->
<!--  core files -->
<!-- <script src="{{ asset('template/back') }}/dist/js/app.min.js"></script>
<script src="{{ asset('template/back') }}/dist/js/app.init.js"></script>
<script src="{{ asset('template/back') }}/dist/js/app-style-switcher.js"></script>
<script src="{{ asset('template/back') }}/dist/js/sidebarmenu.js"></script>
<script src="{{ asset('template/back') }}/dist/js/custom.js"></script> -->
<!--  current page js files -->
<script src="{{ asset('template/back') }}/dist/libs/owl.carousel/dist/owl.carousel.min.js"></script>
<script src="{{ asset('template/back') }}/dist/libs/apexcharts/dist/apexcharts.min.js"></script>
<script src="{{ asset('template/back') }}/dist/js/dashboard.js"></script>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
    // Data yang diterima dari controller (sesuai dengan filter yang diterapkan)
    const purchaseData = @json($purchases-> pluck('total'));
    const purchaseLabels = @json($purchases-> pluck('date'));
    const orderData = @json($orders-> pluck('total'));
    const orderLabels = @json($orders-> pluck('date'));

    // Grafik Purchases (berdasarkan filter)
    const ctx1 = document.getElementById('lineChart1').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: purchaseLabels, // Label berdasarkan tanggal transaksi
            datasets: [{
                label: 'Purchases',
                data: purchaseData,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
                fill: true,
            }],
        },
        options: {
            responsive: true
        },
    });

    // Grafik Orders (berdasarkan filter)
    const ctx2 = document.getElementById('lineChart2').getContext('2d');
    new Chart(ctx2, {
        type: 'line',
        data: {
            labels: orderLabels, // Label berdasarkan tanggal transaksi
            datasets: [{
                label: 'Orders',
                data: orderData,
                borderColor: 'rgba(255, 99, 132, 1)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderWidth: 2,
                fill: true,
            }],
        },
        options: {
            responsive: true
        },
    });
</script>

<script>
    // Data untuk grafik lineChart3
    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var purchasesData = @json($monthlyPurchases);
    var ordersData = @json($monthlyOrders);

    // Menggambar grafik lineChart3 (bar chart)
    var ctx3 = document.getElementById('lineChart3').getContext('2d');
    var lineChart3 = new Chart(ctx3, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                    label: 'Purchase',
                    data: Object.values(purchasesData),
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Order',
                    data: Object.values(ordersData),
                    backgroundColor: 'rgba(153, 102, 255, 0.5)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Data untuk grafik donut (lineChart4)
    var purchaseTotal = @json(array_sum($monthlyPurchases)); // Total Purchase sepanjang tahun
    var orderTotal = @json(array_sum($monthlyOrders)); // Total Order sepanjang tahun

    // Menggambar grafik donut (lineChart4)
    var ctx4 = document.getElementById('lineChart4').getContext('2d');
    var lineChart4 = new Chart(ctx4, {
        type: 'doughnut',
        data: {
            labels: ['Purchase', 'Order'],
            datasets: [{
                data: [purchaseTotal, orderTotal],
                backgroundColor: ['rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw.toLocaleString();
                        }
                    }
                }
            }
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const transactionCategories = @json($transactionCategories);

        // Siapkan data untuk grafik
        const labels = transactionCategories.map(data => data.category_name);
        const kurangData = transactionCategories.map(data => data.kurang);
        const tambahData = transactionCategories.map(data => data.tambah);

        // Buat grafik menggunakan Chart.js
        const ctx = document.getElementById('horizontalBarChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Kurang',
                        data: kurangData,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    },
                    {
                        label: 'Tambah',
                        data: tambahData,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    },
                ]
            },
            options: {
                indexAxis: 'y', // Horizontal bar chart
                scales: {
                    x: {
                        beginAtZero: true,
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const cashTransactions = @json($cashTransactions);

        // Siapkan data untuk grafik
        const labels = cashTransactions.map(data => data.cash_name); // Gunakan cash_name sebagai label
        const kurangData = cashTransactions.map(data => data.kurang);
        const tambahData = cashTransactions.map(data => data.tambah);

        // Buat grafik menggunakan Chart.js
        const ctx = document.getElementById('horizontalBarChartKas').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Kurang',
                        data: kurangData,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                    },
                    {
                        label: 'Tambah',
                        data: tambahData,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    }
                ]
            },
            options: {
                indexAxis: 'y', // Horizontal bar chart
                scales: {
                    x: {
                        beginAtZero: true,
                        stacked: true,
                    },
                    y: {
                        stacked: true,
                    }
                },
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const monthlyCashPurchases = @json($monthlyCashPurchases);

        // Siapkan data untuk grafik
        const cashNames = [...new Set(monthlyCashPurchases.map(data => data.cash_name))];
        const cashIds = [...new Set(monthlyCashPurchases.map(data => data.cash_id))];

        const monthlyData = cashIds.map(cashId => {
            // Ambil data pembelian untuk setiap cash_id
            return {
                label: `Kas ${cashId}`,
                data: Array.from({
                    length: 12
                }, (_, monthIndex) => {
                    const monthData = monthlyCashPurchases.find(item =>
                        item.cash_id === cashId && item.month === (monthIndex + 1)
                    );
                    // Convert to number explicitly to remove leading zeros
                    return monthData ? parseInt(monthData.total_pembelian, 10) : 0;
                }),
                backgroundColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.6)`,
            };
        });

        // Buat grafik menggunakan Chart.js
        const ctx = document.getElementById('monthlyPurchaseChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: cashNames,
                datasets: [{
                    data: monthlyData.map(dataset =>
                        dataset.data.reduce((acc, val) => acc + parseInt(val, 10), 0)
                    ),
                    backgroundColor: monthlyData.map(dataset => dataset.backgroundColor),
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                // Format tooltip dengan nilai yang sudah dikonversi ke number
                                const value = parseInt(tooltipItem.raw, 10);
                                return `${tooltipItem.label}: Rp. ${value.toLocaleString()}`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const monthlyOrderData = @json($monthlyOrderData);

        // Siapkan data untuk grafik
        const cashNames = [...new Set(monthlyOrderData.map(data => data.cash_name))];
        const cashIds = [...new Set(monthlyOrderData.map(data => data.cash_id))];

        const monthlyData = cashIds.map(cashId => {
            // Ambil data penjualan untuk setiap cash_id
            return {
                label: `Kas ${cashId}`,
                data: Array.from({
                    length: 12
                }, (_, monthIndex) => {
                    const monthData = monthlyOrderData.find(item =>
                        item.cash_id === cashId && item.month === (monthIndex + 1)
                    );
                    // Convert to number explicitly to remove leading zeros
                    return monthData ? parseInt(monthData.total_penjualan, 10) : 0;
                }),
                backgroundColor: `rgba(${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, ${Math.floor(Math.random() * 255)}, 0.6)`,
            };
        });

        // Buat grafik menggunakan Chart.js
        const ctx = document.getElementById('monthlyOrderChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: cashNames,
                datasets: [{
                    data: monthlyData.map(dataset =>
                        dataset.data.reduce((acc, val) => acc + parseInt(val, 10), 0)
                    ),
                    backgroundColor: monthlyData.map(dataset => dataset.backgroundColor),
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                // Parse the value as an integer to remove leading zeros
                                const value = parseInt(tooltipItem.raw, 10);
                                return `${tooltipItem.label}: Rp. ${value.toLocaleString()}`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        const monthlyCustomerData = @json($monthlyCustomerData);
        const topCustomers = @json($topCustomers);

        // Siapkan array bulan
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

        // Siapkan datasets untuk masing-masing pelanggan
        const datasets = topCustomers.map((customer, index) => {
            const customerMonthlyData = Array(12).fill(0);

            // Isi data bulanan
            monthlyCustomerData
                .filter(data => data.id === customer.id)
                .forEach(data => {
                    customerMonthlyData[data.month - 1] = data.orders_count;
                });

            const colors = [{
                    bar: 'rgba(41, 98, 255, 0.85)',
                    line: '#2962FF'
                },
                {
                    bar: 'rgba(25, 177, 89, 0.85)',
                    line: '#19B159'
                },
                {
                    bar: 'rgba(255, 77, 77, 0.85)',
                    line: '#FF4D4D'
                }
            ];

            return [{
                // Bar dataset
                type: 'bar',
                label: customer.name + ' (Orders)',
                data: customerMonthlyData,
                backgroundColor: colors[index].bar,
                order: 2
            }, {
                // Line dataset
                type: 'line',
                label: customer.name + ' (Trend)',
                data: customerMonthlyData,
                borderColor: colors[index].line,
                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: colors[index].line,
                fill: false,
                order: 1
            }];
        }).flat();

        const ctx = document.getElementById('monthlyOrderCustomer').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: datasets
            },
            options: {
                responsive: true,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.dataset.type === 'bar') {
                                    return `${context.dataset.label}: ${context.parsed.y} orders`;
                                }
                                return `${context.dataset.label}: ${context.parsed.y}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    });
</script>



<script>
    function toggleFilterOptions(type) {
        // Sembunyikan semua filter terlebih dahulu
        document.getElementById('month_filter').style.display = 'none';
        document.getElementById('date_filter_start').style.display = 'none';
        document.getElementById('date_filter_end').style.display = 'none';

        // Tampilkan filter sesuai dengan pilihan
        if (type === 'month') {
            document.getElementById('month_filter').style.display = 'block';
        } else if (type === 'date') {
            document.getElementById('date_filter_start').style.display = 'block';
            document.getElementById('date_filter_end').style.display = 'block';
        }
    }

    // Inisialisasi filter saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        toggleFilterOptions("{{ request('filter_type') }}");
    });
</script>





@endpush