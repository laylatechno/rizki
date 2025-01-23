<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Set page title and subtitle
        $title = "Halaman Dashboard";
        $subtitle = "Menu Dashboard";

        // Get filter parameters from the request
        $filterType = request('filter_type');
        $startDate = request('start_date');
        $endDate = request('end_date');
        $month = request('month');

        $currentYear = date('Y');
        $currentMonth = Carbon::now()->month;
        $currentMonthName = Carbon::now()->locale('id')->monthName;  // Mengambil nama bulan dalam bahasa Indonesia

        // Get counts of products, users, and customers
        $totalProduk = Product::count();
        $totalPengguna = User::count();
        $totalPelanggan = Customer::count();
        $totalSupplier = Supplier::count();

        // Mendapatkan tanggal sekarang dan tanggal 1 bulan sebelumnya
        $startDate2 = Carbon::now()->startOfMonth(); // 1 bulan berjalan dimulai dari tanggal 1 bulan ini
        $endDate2 = Carbon::now()->endOfMonth(); // akhir bulan ini
        // Count orders dengan status 'Lunas' dan dalam jangka waktu 1 bulan
        $totalOrdersLunas = Order::where('status', 'Lunas')
            ->whereBetween('order_date', [$startDate2, $endDate2])
            ->count();

        // Count purchases dengan status 'Lunas' dan dalam jangka waktu 1 bulan
        $totalPurchasesLunas = Purchase::where('status', 'Lunas')
            ->whereBetween('purchase_date', [$startDate2, $endDate2])
            ->count();





        // Initialize the query for purchases
        $purchasesQuery = Purchase::selectRaw('DATE(purchase_date) as date, SUM(total_cost) as total')
            ->where('status', 'Lunas'); // Filter by 'Lunas' status

        // Apply filter for month if selected
        if ($filterType == 'month' && $month) {
            $currentYear = date('Y'); // Get the current year
            $purchasesQuery->whereYear('purchase_date', $currentYear)
                ->whereMonth('purchase_date', $month);
        }

        // Apply filter for date range if selected
        if ($filterType == 'date' && $startDate && $endDate) {
            $purchasesQuery->whereBetween('purchase_date', [$startDate, $endDate]);
        }

        // Fetch the purchases grouped by date
        $purchases = $purchasesQuery->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Initialize the query for orders
        $ordersQuery = Order::selectRaw('DATE(order_date) as date, SUM(total_cost) as total')
            ->where('status', 'Lunas'); // Filter by 'Lunas' status

        // Apply filter for month if selected
        if ($filterType == 'month' && $month) {
            $currentYear = date('Y'); // Get the current year
            $ordersQuery->whereYear('order_date', $currentYear)
                ->whereMonth('order_date', $month);
        }

        // Apply filter for date range if selected
        if ($filterType == 'date' && $startDate && $endDate) {
            $ordersQuery->whereBetween('order_date', [$startDate, $endDate]);
        }

        // Fetch the orders grouped by date
        $orders = $ordersQuery->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        // Fetch the total purchases per month for the current year
        $monthlyPurchases = Purchase::selectRaw('MONTH(purchase_date) as month, SUM(total_cost) as total')
            ->whereYear('purchase_date', date('Y'))
            ->where('status', 'Lunas')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fetch the total orders per month for the current year
        $monthlyOrders = Order::selectRaw('MONTH(order_date) as month, SUM(total_cost) as total')
            ->whereYear('order_date', date('Y'))
            ->where('status', 'Lunas')
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        // Fill missing months with 0 if no data exists for that month
        $monthlyPurchases = array_replace(array_flip(range(1, 12)), $monthlyPurchases);
        $monthlyOrders = array_replace(array_flip(range(1, 12)), $monthlyOrders);


        // Menampilkan data transaksi per kategori untuk tahun berjalan
        $transactionCategories = Transaction::selectRaw(
            'transaction_categories.name as category_name, 
     SUM(CASE WHEN transaction_categories.parent_type = "kurang" THEN transactions.amount ELSE 0 END) as kurang, 
     SUM(CASE WHEN transaction_categories.parent_type = "tambah" THEN transactions.amount ELSE 0 END) as tambah'
        )
            ->join('transaction_categories', 'transactions.transaction_category_id', '=', 'transaction_categories.id')
            ->whereYear('transactions.date', $currentYear) // Filter berdasarkan tahun
            ->groupBy('transaction_categories.name')
            ->get();

        // Menampilkan data transaksi per cash untuk tahun berjalan
        $cashTransactions = Transaction::selectRaw(
            'cash.name as cash_name, 
     SUM(CASE WHEN transaction_categories.parent_type = "kurang" THEN transactions.amount ELSE 0 END) as kurang, 
     SUM(CASE WHEN transaction_categories.parent_type = "tambah" THEN transactions.amount ELSE 0 END) as tambah'
        )
            ->join('transaction_categories', 'transactions.transaction_category_id', '=', 'transaction_categories.id')
            ->join('cash', 'transactions.cash_id', '=', 'cash.id') // Join ke tabel cash
            ->whereYear('transactions.date', $currentYear) // Filter berdasarkan tahun
            ->groupBy('cash.name')
            ->get();

        // Mengambil total_cost per bulan berdasarkan cash_id untuk tahun berjalan
        $monthlyCashPurchases = DB::table('purchases') // Tabel purchases
            ->selectRaw(
                'MONTH(purchases.purchase_date) as month, 
         cash.name as cash_name, 
         cash.id as cash_id, 
         SUM(purchases.total_cost) as total_pembelian'
            )
            ->join('cash', 'purchases.cash_id', '=', 'cash.id') // Join ke tabel cash
            ->whereYear('purchases.purchase_date', $currentYear) // Filter berdasarkan tahun
            ->groupBy('cash.name', 'cash.id', 'month') // Group by bulan dan cash_id
            ->get();

        // Menghitung total pembelian bulan ini
        $totalMonthlyPurchases = $monthlyCashPurchases->where('month', date('n'))->sum('total_pembelian');


        // Mengambil total penjualan per bulan berdasarkan kas_id untuk tahun berjalan
        $monthlyOrderData = DB::table('orders')
            ->selectRaw(
                'MONTH(orders.order_date) as month, 
     cash.name as cash_name, 
     cash.id as cash_id, 
     SUM(orders.total_cost) as total_penjualan'
            )
            ->join('cash', 'orders.cash_id', '=', 'cash.id') // Join ke tabel cash
            ->whereYear('orders.order_date', $currentYear) // Filter berdasarkan tahun
            ->groupBy('cash.name', 'cash.id', 'month') // Group by bulan dan cash_id
            ->get();

        // Menghitung total penjualan bulan ini
        $totalMonthlyOrders = $monthlyOrderData->where('month', date('n'))->sum('total_penjualan');



        // Fetch best selling products for current year
        $bestSellingProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.order_price) as total_sales')
            )
            ->whereYear('orders.order_date', $currentYear)
            ->where('orders.status', 'Lunas')
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(4) // Get top 2 products
            ->get();

        // Calculate total quantity sold for percentage calculation
        $totalQuantitySold = $bestSellingProducts->sum('total_quantity');

        // Calculate percentage for each product
        $bestSellingProducts = $bestSellingProducts->map(function ($product) use ($totalQuantitySold) {
            $product->percentage = round(($product->total_quantity / $totalQuantitySold) * 100);
            return $product;
        });


        $topSellingProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id as product_id',
                'products.name as product_name',
                'products.image',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.quantity * order_items.order_price) as total_revenue')
            )
            ->whereYear('orders.order_date', $currentYear) // Filter berdasarkan tahun berjalan
            ->whereMonth('orders.order_date', $currentMonth) // Filter berdasarkan bulan berjalan
            ->where('orders.status', 'Lunas') // Filter berdasarkan status 'Lunas'
            ->groupBy('products.id', 'products.name', 'products.image') // Tambahkan image ke groupBy
            ->orderBy('total_sold', 'desc')
            ->limit(5) // Ambil 5 produk teratas
            ->get();

        // Hitung total quantity yang terjual
        $totalSoldQuantity = $topSellingProducts->sum('total_sold');

        // Hitung persentase penjualan untuk setiap produk
        $topSellingProducts = $topSellingProducts->map(function ($product) use ($totalSoldQuantity) {
            $product->sales_percentage = round(($product->total_sold / $totalSoldQuantity) * 100);
            return $product;
        });


        $topCustomers = DB::table('orders')
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->select(
                'customers.id',
                'customers.name',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total_cost) as total_spending')
            )
            ->whereYear('orders.order_date', $currentYear)
            ->where('orders.status', 'Lunas')
            ->groupBy('customers.id', 'customers.name')
            ->orderBy('total_orders', 'desc')
            ->limit(3)
            ->get();


        $monthlyCustomerData = DB::table('orders')
            ->select(
                'customers.id',
                'customers.name',
                DB::raw('MONTH(orders.order_date) as month'),
                DB::raw('COUNT(orders.id) as orders_count'),
                DB::raw('SUM(orders.total_cost) as total_amount')
            )
            ->join('customers', 'orders.customer_id', '=', 'customers.id')
            ->whereIn('customers.id', $topCustomers->pluck('id'))
            ->whereYear('orders.order_date', $currentYear)
            ->where('orders.status', 'Lunas')
            ->groupBy('customers.id', 'customers.name', 'month')
            ->get();



        return view('home', compact(
            'title',
            'subtitle',
            'totalProduk',
            'totalPengguna',
            'totalPelanggan',
            'totalSupplier',
            'totalOrdersLunas',
            'totalPurchasesLunas',
            'purchases',
            'orders',
            'monthlyPurchases',
            'monthlyOrders',
            'currentYear',
            'currentMonth',
            'currentMonthName',
            'transactionCategories',
            'cashTransactions',
            'monthlyCashPurchases',
            'totalMonthlyPurchases',
            'monthlyOrderData',
            'totalMonthlyOrders',
            'bestSellingProducts',
            'topSellingProducts',
            'topCustomers',
            'monthlyCustomerData'
        ));
    }
}
