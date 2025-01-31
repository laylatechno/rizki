<?php

use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\TravelRouteController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\SliderController;

use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\CashController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LogHistoriController;
use App\Http\Controllers\MenuGroupsController;
use App\Http\Controllers\MenuItemsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransactionCategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TutorialController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\HtmlMinifier;



Auth::routes();




Route::middleware([HtmlMinifier::class])->group(function () {
    Route::get('/', [BerandaController::class, 'index'])->name('beranda');
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/blog', [BerandaController::class, 'blog'])->name('blog');
    Route::get('/blog/{slug}', [BerandaController::class, 'blog_detail'])->name('blog.blog_detail');
    Route::get('/kontak', [BerandaController::class, 'kontak'])->name('kontak');
    Route::post('/kirim_kontak', [BerandaController::class, 'storeContact'])->name('kirim_kontak');
});

// Tambahkan di dalam middleware group HtmlMinifier
Route::get('sitemap.xml', function() {
    return response()->file(public_path('sitemap.xml'));
});


Route::group(['middleware' => ['auth']], function () {
    Route::resource('blog_categories', BlogCategoryController::class);
    Route::resource('blogs', BlogController::class);

    Route::resource('contacts', ContactController::class);

    Route::resource('galleries', GalleryController::class);

    Route::resource('travel_routes', TravelRouteController::class);

    Route::resource('fleets', FleetController::class);

    Route::resource('testimonials', TestimonialController::class);

    Route::resource('sliders', SliderController::class);


    Route::get('/tutorial-status', [TutorialController::class, 'getTutorialStatus']);
    Route::post('/set-tutorial-status', [TutorialController::class, 'setTutorialStatus']);

    Route::resource('adjustments', AdjustmentController::class);
    Route::get('/adjustments/print/{id}', [AdjustmentController::class, 'print'])->name('adjustments.print');


    Route::resource('stock_opname', StockOpnameController::class);
    Route::get('/stock-opname/print/{id}', [StockOpnameController::class, 'print'])->name('stock_opname.print');

    // Route::resource('purchase_reports', ReportController::class);
    Route::get('report/purchase_reports', [ReportController::class, 'purchase_report'])->name('report.purchase_reports');
    Route::get('report/purchase_reports/export', [ReportController::class, 'exportExcelPurchase'])->name('report.purchase_reports.export');
    Route::get('report/purchase_reports/export-pdf', [ReportController::class, 'exportPdfPurchase'])->name('report.purchase_reports.export_pdf');
    Route::get('report/purchase_reports/preview-pdf', [ReportController::class, 'previewPdfPurchase'])->name('report.purchase_reports.preview_pdf');




    Route::get('report/order_reports', [ReportController::class, 'order_report'])->name('report.order_reports');
    Route::get('report/order_reports/export', [ReportController::class, 'exportExcelOrder'])->name('report.order_reports.export');
    Route::get('report/order_reports/export-pdf', [ReportController::class, 'exportPdfOrder'])->name('report.order_reports.export_pdf');
    Route::get('report/order_reports/preview-pdf', [ReportController::class, 'previewPdfOrder'])->name('report.order_reports.preview_pdf');



    Route::get('/report/product_reports', [ReportController::class, 'product_report'])->name('report.product_reports');
    Route::get('/report/product-reports/export', [ReportController::class, 'exportExcelProduct'])->name('report.product_reports.export');
    Route::get('/report/product-reports/export-pdf', [ReportController::class, 'exportPdfProduct'])->name('report.product_reports.export_pdf');
    Route::get('/report/product-reports/preview-pdf', [ReportController::class, 'previewPdfProduct'])->name('report.product_reports.preview_pdf');



    Route::get('/report/profit_reports', [ReportController::class, 'profit_report'])->name('report.profit_reports');
    Route::get('/report/profit-reports/export', [ReportController::class, 'exportExcelProfit'])->name('report.profit_reports.export');
    Route::get('/report/profit-reports/export-pdf', [ReportController::class, 'exportPdfProfit'])->name('report.profit_reports.export_pdf');
    Route::get('/report/profit-reports/preview-pdf', [ReportController::class, 'previewPdfProfit'])->name('report.profit_reports.preview_pdf');




    Route::get('/report/top_product_reports', [ReportController::class, 'top_product_report'])->name('report.top_product_reports');
    Route::get('/report/top_product-reports/export', [ReportController::class, 'exportExcelTopProduct'])->name('report.top_product_reports.export');
    Route::get('/report/top_product-reports/export-pdf', [ReportController::class, 'exportPdfTopProduct'])->name('report.top_product_reports.export_pdf');
    Route::get('/report/top_product-reports/preview-pdf', [ReportController::class, 'previewPdfTopProduct'])->name('report.top_product_reports.preview_pdf');




    Route::resource('transactions', TransactionController::class);
    Route::resource('transaction_categories', TransactionCategoryController::class);
    Route::resource('cash', CashController::class);
    Route::resource('purchases', PurchaseController::class);
    Route::get('/purchases/{id}/print-invoice', [PurchaseController::class, 'printInvoice'])->name('purchases.print_invoice');
    Route::put('purchases/{purchase}', [PurchaseController::class, 'update'])->name('purchases.update');
    Route::resource('suppliers', SupplierController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('orders', OrderController::class);
    Route::get('/orders/{id}/print-invoice', [OrderController::class, 'printInvoice'])->name('orders.print_invoice');
    Route::get('/orders/{id}/print-struk', [OrderController::class, 'printStruk'])->name('orders.print_struk');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::resource('units', UnitController::class);
    Route::resource('products', ProductController::class);
    Route::get('/get-product-price', [ProductController::class, 'getProductPrice']);
    Route::post('/products/generate-barcode', [ProductController::class, 'generateBarcode'])->name('products.generate_barcode');
    Route::get('/get-product-by-barcode', [ProductController::class, 'getProductByBarcode']);
    Route::resource('categories', CategoryController::class);
    Route::resource('routes', RouteController::class);
    Route::get('/generate-routes', [RouteController::class, 'generateRoutes'])->name('routes.generate');
    Route::resource('log_histori', LogHistoriController::class);
    Route::get('/log-histori/delete-all', [LogHistoriController::class, 'deleteAll'])->name('log-histori.delete-all');
    Route::resource('roles', RolesController::class);
    Route::resource('users', UsersController::class);
    Route::resource('permissions', PermissionsController::class);
    Route::resource('profil', ProfilController::class);
    Route::put('/profil/update_setting/{id}', [ProfilController::class, 'update_setting'])->name('profil.update_setting');
    Route::resource('menu_groups', MenuGroupsController::class);
    Route::resource('menu_items', MenuItemsController::class);
    Route::post('menu-items/update-positions', [MenuItemsController::class, 'updatePositions'])->name('menu_items.update_positions');
    Route::post('menu-groups/update-positions', [MenuGroupsController::class, 'updatePositions'])->name('menu_groups.update_positions');
    Route::get('/create-resource', [ResourceController::class, 'createForm'])->name('resource.create');
    Route::post('/create-resource', [ResourceController::class, 'createResource'])->name('resource.store');
});
