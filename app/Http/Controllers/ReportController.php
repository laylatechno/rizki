<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Exports\PurchaseReportExport;
use App\Exports\OrderReportExport;
use App\Exports\ProductReportExport;
use App\Exports\ProfitReportExport;
use App\Exports\TopProductReportExport;
use App\Models\Cash;
use App\Models\Category;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\Profit;
use App\Models\Supplier;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{

    function __construct()
    {

        $this->middleware('permission:purchasereport-list', ['only' => ['purchase_report']]);
        $this->middleware('permission:orderreport-list', ['only' => ['order_report']]);
        $this->middleware('permission:productreport-list', ['only' => ['product_report']]);
        $this->middleware('permission:profitreport-list', ['only' => ['profit_report']]);
        $this->middleware('permission:topproductreport-list', ['only' => ['top_product_report']]);
    }





    // Laporan Pembelian
    public function purchase_report(Request $request)
    {
        $title = "Laporan Pembelian";
        $subtitle = "Menu Laporan Pembelian";

        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $status = $request->get('status');
        $supplierIds = $request->get('supplier_id', []);
        $typePayment = $request->get('type_payment');
        $cashIds = $request->get('cash_id', []);

        $data_purchases = Purchase::with(['supplier', 'user'])
            ->whereBetween('purchase_date', [$startDate, $endDate]);

        // Filter berdasarkan status
        if (!empty($status)) {
            $data_purchases->where('status', $status);
        }

        // Filter berdasarkan supplier_id (jika ada)
        if (!empty($supplierIds)) {
            $data_purchases->whereIn('supplier_id', $supplierIds);
        }

        // Filter berdasarkan metode pembayaran
        if (!empty($typePayment)) {
            $data_purchases->where('type_payment', $typePayment);
        }

        // Filter berdasarkan cash_id (jika ada)
        if (!empty($cashIds)) {
            $data_purchases->whereIn('cash_id', $cashIds);
        }

        $data_purchases = $data_purchases->orderBy('id', 'desc')->get();

        $data_products = Product::all();
        $suppliers = Supplier::all();
        $cashes = Cash::all(); // Pastikan Anda mengimpor model Cash

        return view('report.purchase_report.index', compact(
            'data_purchases',
            'data_products',
            'suppliers',
            'cashes',
            'title',
            'subtitle'
        ));
    }

    // Update export methods to include new filters
    public function exportExcelPurchase(Request $request)
    {
        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $typePayment = $request->get('type_payment');
        $cashIds = $request->get('cash_id', []);

        return Excel::download(new PurchaseReportExport($startDate, $endDate, $typePayment, $cashIds), 'Laporan_Pembelian.xlsx');
    }


    public function exportPdfPurchase(Request $request)
    {
        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $typePayment = $request->get('type_payment');
        $cashIds = $request->get('cash_id', []);

        // Query data berdasarkan filter
        $data_purchases = Purchase::with(['supplier', 'user', 'cash'])
            ->whereBetween('purchase_date', [$startDate, $endDate]);

        if (!empty($typePayment)) {
            $data_purchases->where('type_payment', $typePayment);
        }

        if (!empty($cashIds)) {
            $data_purchases->whereIn('cash_id', $cashIds);
        }

        $data_purchases = $data_purchases->orderBy('id', 'desc')->get();

        $title = "Laporan Pembelian";
        $subtitle = "Menu Laporan Pembelian";

        // Render view ke dalam PDF dengan orientasi landscape
        $pdf = Pdf::loadView('report.purchase_report.pdf', compact(
            'data_purchases',
            'title',
            'subtitle',
            'startDate',
            'endDate'
        ))->setPaper('a4', 'landscape');

        // Unduh PDF dengan nama file
        return $pdf->download('Laporan_Pembelian.pdf');
    }


    public function previewPdfPurchase(Request $request)
    {
        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $status = $request->get('status');
        $supplierIds = $request->get('supplier_id', []);
        $typePayment = $request->get('type_payment');
        $cashIds = $request->get('cash_id', []);

        $data_purchases = Purchase::with(['supplier', 'user'])
            ->whereBetween('purchase_date', [$startDate, $endDate]);

        // Filter berdasarkan status
        if (!empty($status)) {
            $data_purchases->where('status', $status);
        }

        // Filter berdasarkan supplier_id (jika ada)
        if (!empty($supplierIds)) {
            $data_purchases->whereIn('supplier_id', $supplierIds);
        }

        // Filter berdasarkan metode pembayaran
        if (!empty($typePayment)) {
            $data_purchases->where('type_payment', $typePayment);
        }

        // Filter berdasarkan cash_id (jika ada)
        if (!empty($cashIds)) {
            $data_purchases->whereIn('cash_id', $cashIds);
        }

        $data_purchases = $data_purchases->orderBy('id', 'desc')->get();

        $title = "Laporan Pembelian";
        $subtitle = "Menu Laporan Pembelian";

        // Render view ke dalam PDF dengan orientasi landscape
        $pdf = Pdf::loadView('report.purchase_report.pdf', compact(
            'data_purchases',
            'title',
            'subtitle',
            'startDate',
            'endDate'
        ))->setPaper('a4', 'landscape');

        // Tampilkan PDF di browser
        return $pdf->stream('Laporan_Pembelian.pdf');
    }








    // Laporan Penjualan
    public function order_report(Request $request)
    {
        $title = "Laporan Penjualan";
        $subtitle = "Menu Laporan Penjualan";

        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $status = $request->get('status');
        $customerIds = $request->get('customer_id', []);
        $typePayment = $request->get('type_payment');
        $cashIds = $request->get('cash_id', []);

        $data_orders = Order::with(['customer', 'user'])
            ->whereBetween('order_date', [$startDate, $endDate]);

        // Filter berdasarkan status
        if (!empty($status)) {
            $data_orders->where('status', $status);
        }

        // Filter berdasarkan customer_id (jika ada)
        if (!empty($customerIds)) {
            $data_orders->whereIn('customer_id', $customerIds);
        }

        // Filter berdasarkan metode pembayaran
        if (!empty($typePayment)) {
            $data_orders->where('type_payment', $typePayment);
        }

        // Filter berdasarkan cash_id (jika ada)
        if (!empty($cashIds)) {
            $data_orders->whereIn('cash_id', $cashIds);
        }

        $data_orders = $data_orders->orderBy('id', 'desc')->get();

        $data_products = Product::all();
        $customers = Customer::all();
        $cashes = Cash::all(); // Pastikan Anda mengimpor model Cash

        return view('report.order_report.index', compact(
            'data_orders',
            'data_products',
            'customers',
            'cashes',
            'title',
            'subtitle'
        ));
    }

    public function exportExcelOrder(Request $request)
    {
        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $typePayment = $request->get('type_payment');
        $cashIds = $request->get('cash_id', []);

        return Excel::download(new OrderReportExport($startDate, $endDate, $typePayment, $cashIds), 'Laporan_Penjualan.xlsx');
    }

    public function exportPdfOrder(Request $request)
    {
        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $status = $request->get('status');
        $customerIds = $request->get('customer_id', []);
        $typePayment = $request->get('type_payment');
        $cashIds = $request->get('cash_id', []);

        $data_orders = Order::with(['customer', 'user'])
            ->whereBetween('order_date', [$startDate, $endDate]);

        // Filter berdasarkan status
        if (!empty($status)) {
            $data_orders->where('status', $status);
        }

        // Filter berdasarkan customer_id (jika ada)
        if (!empty($customerIds)) {
            $data_orders->whereIn('customer_id', $customerIds);
        }

        // Filter berdasarkan metode pembayaran
        if (!empty($typePayment)) {
            $data_orders->where('type_payment', $typePayment);
        }

        // Filter berdasarkan cash_id (jika ada)
        if (!empty($cashIds)) {
            $data_orders->whereIn('cash_id', $cashIds);
        }

        $data_orders = $data_orders->orderBy('id', 'desc')->get();

        $title = "Laporan Penjualan";
        $subtitle = "Menu Laporan Penjualan";

        // Render view ke dalam PDF dengan orientasi landscape
        $pdf = Pdf::loadView('report.order_report.pdf', compact(
            'data_orders',
            'title',
            'subtitle',
            'startDate',
            'endDate'
        ))->setPaper('a4', 'landscape');

        // Unduh PDF dengan nama file
        return $pdf->download('Laporan_Penjualan.pdf');
    }


    public function previewPdfOrder(Request $request)
    {
        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $status = $request->get('status');
        $customerIds = $request->get('customer_id', []);
        $typePayment = $request->get('type_payment');
        $cashIds = $request->get('cash_id', []);

        $data_orders = Order::with(['customer', 'user'])
            ->whereBetween('order_date', [$startDate, $endDate]);

        // Filter berdasarkan status
        if (!empty($status)) {
            $data_orders->where('status', $status);
        }

        // Filter berdasarkan customer_id (jika ada)
        if (!empty($customerIds)) {
            $data_orders->whereIn('customer_id', $customerIds);
        }

        // Filter berdasarkan metode pembayaran
        if (!empty($typePayment)) {
            $data_orders->where('type_payment', $typePayment);
        }

        // Filter berdasarkan cash_id (jika ada)
        if (!empty($cashIds)) {
            $data_orders->whereIn('cash_id', $cashIds);
        }

        $data_orders = $data_orders->orderBy('id', 'desc')->get();

        $title = "Laporan Penjualan";
        $subtitle = "Menu Laporan Penjualan";

        // Render view ke dalam PDF dengan orientasi landscape
        $pdf = Pdf::loadView('report.order_report.pdf', compact(
            'data_orders',
            'title',
            'subtitle',
            'startDate',
            'endDate'
        ))->setPaper('a4', 'landscape');

        // Tampilkan PDF di browser
        return $pdf->stream('Laporan_Penjualan.pdf');
    }







    // Laporan Produk
    public function product_report(Request $request)
    {
        $title = "Laporan Penjualan";
        $subtitle = "Menu Laporan Penjualan";

        // Mendapatkan nilai filter dari request
        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $status = $request->get('status');
        $customerIds = $request->get('customer_id', []);
        $productIds = $request->get('product_id', []);

        $data_orders = Order::with(['customer', 'user', 'orderItems.product'])
            ->whereBetween('order_date', [$startDate, $endDate]);

        // Filter berdasarkan status, jika ada
        if (!empty($status)) {
            $data_orders->where('status', $status);
        }

        // Filter berdasarkan customer_id, jika ada
        if (!empty($customerIds)) {
            $data_orders->whereIn('customer_id', $customerIds);
        }

        // Filter berdasarkan product_id, jika ada
        if (!empty($productIds)) {
            $data_orders->whereHas('orderItems', function ($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            })->with(['orderItems' => function ($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            }]);
        }

        // Ambil data order yang sudah difilter
        $data_orders = $data_orders->orderBy('id', 'desc')->get();

        // Mengambil semua produk dan pelanggan
        $data_products = Product::all();
        $customers = Customer::all();

        return view('report.product_report.index', compact(
            'data_orders',
            'data_products',
            'customers',
            'title',
            'subtitle'
        ));
    }

    private function getFilteredOrders(Request $request)
    {
        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $status = $request->get('status');
        $customerIds = $request->get('customer_id', []);
        $productIds = $request->get('product_id', []);

        $data_orders = Order::with(['customer', 'user', 'orderItems.product'])
            ->whereBetween('order_date', [$startDate, $endDate]);

        if (!empty($status)) {
            $data_orders->where('status', $status);
        }

        if (!empty($customerIds)) {
            $data_orders->whereIn('customer_id', $customerIds);
        }

        if (!empty($productIds)) {
            $data_orders->whereHas('orderItems', function ($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            })->with(['orderItems' => function ($query) use ($productIds) {
                $query->whereIn('product_id', $productIds);
            }]);
        }

        return $data_orders->orderBy('id', 'desc')->get();
    }

    public function exportExcelProduct(Request $request)
    {
        $data_orders = $this->getFilteredOrders($request);
        return Excel::download(new ProductReportExport($data_orders), 'Laporan_Penjualan_' . date('YmdHis') . '.xlsx');
    }

    public function exportPdfProduct(Request $request)
    {
        $data_orders = $this->getFilteredOrders($request);

        // Hitung total
        $totalQuantity = 0;
        $totalOrderPrice = 0;
        $grandTotal = 0;

        foreach ($data_orders as $order) {
            foreach ($order->orderItems as $orderItem) {
                $totalQuantity += $orderItem->quantity;
                $totalOrderPrice += $orderItem->order_price;
                $grandTotal += $orderItem->quantity * $orderItem->order_price;
            }
        }

        $pdf = PDF::loadView('report.product_report.pdf', [
            'data_orders' => $data_orders,
            'totalQuantity' => $totalQuantity,
            'totalOrderPrice' => $totalOrderPrice,
            'grandTotal' => $grandTotal,
            'startDate' => $request->get('start_date', now()->format('Y-m-d')),
            'endDate' => $request->get('end_date', now()->format('Y-m-d'))
        ])->setPaper('a4', 'landscape'); // Atur ke landscape

        return $pdf->download('Laporan_Penjualan_' . date('YmdHis') . '.pdf');
    }

    public function  previewPdfProduct(Request $request)
    {
        $data_orders = $this->getFilteredOrders($request);

        // Hitung total
        $totalQuantity = 0;
        $totalOrderPrice = 0;
        $grandTotal = 0;

        foreach ($data_orders as $order) {
            foreach ($order->orderItems as $orderItem) {
                $totalQuantity += $orderItem->quantity;
                $totalOrderPrice += $orderItem->order_price;
                $grandTotal += $orderItem->quantity * $orderItem->order_price;
            }
        }

        $pdf = PDF::loadView('report.product_report.pdf', [
            'data_orders' => $data_orders,
            'totalQuantity' => $totalQuantity,
            'totalOrderPrice' => $totalOrderPrice,
            'grandTotal' => $grandTotal,
            'startDate' => $request->get('start_date', now()->format('Y-m-d')),
            'endDate' => $request->get('end_date', now()->format('Y-m-d'))
        ])->setPaper('a4', 'landscape'); // Atur ke landscape

        return $pdf->stream('Laporan_Penjualan_Preview.pdf');
    }


    public function profit_report(Request $request)
    {
        $title = "Laporan Laba Rugi";
        $subtitle = "Menu Laporan Laba Rugi";

        // Ambil semua data profit_loss dan filter berdasarkan tanggal serta kategori transaksi
        $profitLoss = Profit::with(['cash', 'transactionCategory', 'order', 'purchase'])
            ->when($request->start_date, function ($query) use ($request) {
                $query->where('date', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->where('date', '<=', $request->end_date);
            })
            ->when($request->cash_id, function ($query) use ($request) {
                $query->where('cash_id', $request->cash_id);
            })
            // Filter berdasarkan kategori transaksi
            ->when($request->category, function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->orderBy('date', 'asc') // Menyortir berdasarkan tanggal
            ->get();

        // Mengambil semua data kas
        $cashes = Cash::all();

        // Kirim variabel ke tampilan
        return view('report.profit_report.index', compact(
            'title',
            'subtitle',
            'cashes',
            'profitLoss'
        ));
    }

    public function exportExcelProfit(Request $request)
    {
        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $category = $request->get('category');
        $cashIds = $request->get('cash_id', []); // Default kosong jika tidak ada input

        // Jika cash_id kosong, ambil semua data tanpa filter kas
        $cashIds = empty($cashIds) ? null : (is_array($cashIds) ? $cashIds : [$cashIds]);

        // Inisiasi data untuk export
        $profitQuery = Profit::query();

        // Filter tanggal
        $profitQuery->whereBetween('date', [$startDate, $endDate]);

        // Filter kategori
        if ($category) {
            $profitQuery->where('category', $category);
        }

        // Filter cash_id jika ada
        if ($cashIds) {
            $profitQuery->whereIn('cash_id', $cashIds);
        }

        // Ambil data
        $profits = $profitQuery->get();

        // Jika data kosong, kirimkan respons atau pesan error
        if ($profits->isEmpty()) {
            return redirect()->back()->with('error', 'Data tidak ditemukan untuk filter yang dipilih.');
        }

        // Export data ke Excel
        return Excel::download(new ProfitReportExport($startDate, $endDate, $category, $cashIds), 'Laporan_Produk_Terlaris.xlsx');
    }

    public function exportPdfProfit(Request $request)
    {
        // Ambil semua data profit_loss dan filter berdasarkan tanggal serta kategori transaksi
        $profitLoss = Profit::with(['cash', 'transactionCategory', 'order', 'purchase'])
            ->when($request->start_date, function ($query) use ($request) {
                $query->where('date', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($query) use ($request) {
                $query->where('date', '<=', $request->end_date);
            })
            // Pastikan cash_id adalah array, meskipun hanya satu nilai yang dipilih
            ->when($request->cash_id, function ($query) use ($request) {
                // Pastikan cash_id dalam bentuk array
                $cashIds = is_array($request->cash_id) ? $request->cash_id : [$request->cash_id];
                $query->whereIn('cash_id', $cashIds);
            })
            ->when($request->category, function ($query) use ($request) {
                $query->where('category', $request->category);
            })
            ->orderBy('date', 'asc')
            ->get();

        // Menyiapkan data untuk dikirim ke view
        $title = "Laporan Laba Rugi";
        $subtitle = "Menu Laporan Laba Rugi";

        // Mengambil semua data kas (untuk digunakan di dalam view jika perlu)
        $cashes = Cash::all();

        // Load view PDF dan kirimkan data ke view dengan pengaturan orientasi dan ukuran kertas A4 landscape
        $pdf = PDF::loadView('report.profit_report.pdf', compact('profitLoss', 'title', 'subtitle', 'cashes'))
            ->setPaper('a4', 'landscape'); // Menetapkan ukuran kertas A4 dengan orientasi landscape

        // Mengunduh PDF
        return $pdf->download('laporan_laba_rugi.pdf');
    }

    public function previewPdfProfit(Request $request)
    {


        $startDate = $request->get('start_date', now()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $category = $request->get('category');
        $cashIds = $request->get('cash_id', []);  // Menangani cash_id jika kosong

        // Pastikan cash_id adalah array, jika hanya satu nilai yang dipilih
        if ($cashIds && !is_array($cashIds)) {
            $cashIds = [$cashIds];  // Ubah menjadi array jika hanya satu nilai
        }

        // Ambil data profit berdasarkan tanggal, kategori, dan cash_id yang diberikan
        $profitLoss = Profit::with(['cash', 'transaction', 'order', 'purchase'])
            ->whereBetween('date', [$startDate, $endDate]);

        // Filter berdasarkan kategori jika ada
        if ($category) {
            $profitLoss->where('category', $category);
        }

        // Hanya menerapkan filter cash_id jika tidak kosong (bisa dipilih semua jika kosong)
        if (!empty($cashIds)) {
            $profitLoss->whereIn('cash_id', $cashIds);
        }

        // Ambil data dan urutkan berdasarkan tanggal
        $profitLoss = $profitLoss->orderBy('date', 'desc')->get();

        // Cek apakah data kosong
        if ($profitLoss->isEmpty()) {
            return view('errors.no_data'); // Tampilkan pesan jika data kosong
        }

        $title = "Laporan Laba Rugi";
        $subtitle = "Menu Laporan Laba Rugi";

        // Buat PDF
        $pdf = Pdf::loadView('report.profit_report.pdf', compact('profitLoss', 'title', 'subtitle', 'startDate', 'endDate'))
            ->setPaper('a4', 'landscape');

        // Tampilkan PDF dalam browser
        return $pdf->stream('Laporan_Laba_Rugi.pdf');
    }




    public function top_product_report(Request $request)
    {
        $title = "Laporan Produk Terlaris";
        $subtitle = "Menu Laporan Produk Terlaris";

        // Ambil tanggal awal dan akhir dari request, jika tidak ada gunakan awal dan akhir bulan
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        $status = $request->status; // Status filter dari order
        $categoryId = $request->category; // ID kategori filter

        // Ambil data order_items dengan join produk
        $topProduct = OrderItem::with('product.category') // Relasi dengan produk dan kategori
            ->whereHas('order', function ($query) use ($startDate, $endDate, $status) {
                // Filter berdasarkan tanggal order
                $query->whereBetween('order_date', [$startDate, $endDate]);
                if ($status) {
                    $query->where('status', $status); // Filter berdasarkan status jika ada
                }
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                // Filter berdasarkan kategori jika dipilih
                $query->whereHas('product', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                });
            })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'), // Hitung total quantity
                DB::raw('SUM(total_price) as total_price') // Hitung total harga
            )
            ->groupBy('product_id') // Kelompokkan berdasarkan produk
            ->orderByDesc('total_quantity') // Urutkan berdasarkan quantity tertinggi
            ->get();

        // Ambil semua kategori untuk dropdown filter
        $categories = Category::all();

        // Return view dengan data yang diambil
        return view('report.top_product_report.index', compact(
            'title',
            'subtitle',
            'topProduct',
            'categories',
            'startDate',
            'endDate',
            'status',
            'categoryId'
        ));
    }


    public function exportExcelTopProduct(Request $request)
    {
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        $status = $request->status;
        $categoryId = $request->category;
    
        $topProduct = OrderItem::with('product.category')
            ->whereHas('order', function ($query) use ($startDate, $endDate, $status) {
                $query->whereBetween('order_date', [$startDate, $endDate]);
                if ($status) {
                    $query->where('status', $status);
                }
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->whereHas('product', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                });
            })
            ->select('product_id', DB::raw('SUM(quantity) as total_quantity'), DB::raw('SUM(total_price) as total_price'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->get();
    
        // Menambahkan data ke dalam laporan Excel
        return Excel::download(new TopProductReportExport($topProduct), 'Laporan_Produk_Terlaris.xlsx');
    }
    

    public function exportPdfTopProduct(Request $request)
    {
        $title = "Laporan Produk Terlaris";
        $subtitle = "Menu Laporan Produk Terlaris";
    
        // Ambil tanggal awal dan akhir dari request, jika tidak ada gunakan awal dan akhir bulan
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        $status = $request->status; // Status filter dari order
        $categoryId = $request->category; // ID kategori filter
    
        // Query untuk mendapatkan produk terlaris
        $topProduct = OrderItem::with('product.category') // Relasi dengan produk dan kategori
            ->whereHas('order', function ($query) use ($startDate, $endDate, $status) {
                // Filter berdasarkan tanggal order
                $query->whereBetween('order_date', [$startDate, $endDate]);
                if ($status) {
                    $query->where('status', $status); // Filter berdasarkan status jika ada
                }
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                // Filter berdasarkan kategori jika dipilih
                $query->whereHas('product', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                });
            })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'), // Hitung total quantity
                DB::raw('SUM(total_price) as total_price') // Hitung total harga
            )
            ->groupBy('product_id') // Kelompokkan berdasarkan produk
            ->orderByDesc('total_quantity') // Urutkan berdasarkan quantity tertinggi
            ->get();
    
        // Generate PDF
        $pdf = PDF::loadView('report.top_product_report.pdf', compact(
            'title',
            'subtitle',
            'topProduct',
            'startDate',
            'endDate'
        ))->setPaper('a4', 'landscape');
    
        // Unduh PDF
        return $pdf->download('Laporan_Produk_Terlaris.pdf');
    }
    

    public function previewPdfTopProduct(Request $request)
    {
        $title = "Laporan Produk Terlaris";
        $subtitle = "Menu Laporan Produk Terlaris";
    
        // Ambil tanggal awal dan akhir dari request, jika tidak ada gunakan awal dan akhir bulan
        $startDate = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        $status = $request->status; // Status filter dari order
        $categoryId = $request->category; // ID kategori filter
    
        // Ambil data order_items dengan join produk
        $topProduct = OrderItem::with('product.category')
            ->whereHas('order', function ($query) use ($startDate, $endDate, $status) {
                $query->whereBetween('order_date', [$startDate, $endDate]);
                if ($status) {
                    $query->where('status', $status); // Filter berdasarkan status jika ada
                }
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->whereHas('product', function ($query) use ($categoryId) {
                    $query->where('category_id', $categoryId);
                });
            })
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'), // Hitung total quantity
                DB::raw('SUM(total_price) as total_price') // Hitung total harga
            )
            ->groupBy('product_id') // Kelompokkan berdasarkan produk
            ->orderByDesc('total_quantity') // Urutkan berdasarkan quantity tertinggi
            ->get();
    
        // Buat PDF
        $pdf = Pdf::loadView('report.top_product_report.pdf', compact(
            'title',
            'subtitle',
            'topProduct',
            'startDate',
            'endDate'
        ))->setPaper('a4', 'landscape');
    
        // Tampilkan PDF dalam browser
        return $pdf->stream('Laporan_Produk_Terlaris.pdf');
    }
    











    /**
     * Display a listing of the resource.
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
