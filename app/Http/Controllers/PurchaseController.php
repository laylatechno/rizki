<?php

namespace App\Http\Controllers;

use App\Models\Cash;
use App\Models\LogHistori;
use App\Models\Product;
use App\Models\Profil;
use App\Models\Profit;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:purchase-list|purchase-create|purchase-edit|purchase-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:purchase-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:purchase-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:purchase-delete', ['only' => ['destroy']]);
    }

    private function simpanLogHistori($aksi, $tabelAsal, $idEntitas, $pengguna, $dataLama, $dataBaru)
    {
        $log = new LogHistori();
        $log->tabel_asal = $tabelAsal;
        $log->id_entitas = $idEntitas;
        $log->aksi = $aksi;
        $log->waktu = now();
        $log->pengguna = $pengguna;
        $log->data_lama = $dataLama;
        $log->data_baru = $dataBaru;
        $log->save();
    }


    public function index()
    {
        $title = "Halaman Pembelian";
        $subtitle = "Menu Pembelian";

        // Eager loading supplier dan user
        $data_purchases = Purchase::with(['supplier', 'user'])->orderBy('id', 'desc')->get();

        $data_products = Product::all();

        // Kirim semua data ke view
        return view('purchase.index', compact('data_purchases', 'data_products', 'title', 'subtitle'));
    }

    public function printInvoice($id)
    {
        // Ambil data pembelian berdasarkan ID
        $purchase = Purchase::with(['supplier', 'user', 'purchaseItems.product'])->findOrFail($id);

        // Kirim data pembelian ke view
        return view('purchase.print', compact('purchase'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = "Halaman Tambah Pembelian";
        $subtitle = "Menu Tambah Pembelian";

        // Mengambil data yang diperlukan
        $data_users = User::all();
        $data_products = Product::all();
        $data_suppliers = Supplier::all();
        $data_purchases = Purchase::all();
        $data_cashes = Cash::all();

        // Mendapatkan kode pembelian terbaru dari database
        $latestPurchase = Purchase::latest()->first();
        $no_purchase = '';

        // Mengambil alias dari tabel profil
        $alias = Profil::first()->alias ?? 'LTPOS';  // Default 'LTPOS' jika alias kosong

        // Jika belum ada pembelian sebelumnya
        if (!$latestPurchase) {
            $no_purchase = $alias . '-' . date('Ymd') . '-000001-PCS';
        } else {
            // Memecah kode pembelian untuk mendapatkan nomor urut
            $parts = explode('-', $latestPurchase->no_purchase);
            $nomor_urut = intval($parts[2]) + 1;

            // Format ulang nomor urut agar memiliki panjang 6 digit
            $nomor_urut_format = str_pad($nomor_urut, 6, '0', STR_PAD_LEFT);

            // Menggabungkan kode pembelian baru
            $no_purchase = $alias . '-' . date('Ymd') . '-' . $nomor_urut_format . '-PCS';
        }

        // Menampilkan view dengan data yang diperlukan
        return view('purchase.create', compact('data_cashes', 'data_purchases', 'data_products', 'data_users', 'data_suppliers', 'title', 'subtitle', 'no_purchase'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data pembelian
        $request->validate([
            'purchase_date' => 'required|date',
            'total_cost' => 'required|numeric',
            'product_id' => 'required|array',
            'quantity' => 'required|array',
            'image' => 'mimes:jpg,jpeg,png,gif|max:4048', // Max 4 MB
        ], [
            'image.mimes' => 'Bukti yang dimasukkan hanya diperbolehkan berekstensi JPG, JPEG, PNG dan GIF',
            'image.max' => 'Ukuran image tidak boleh lebih dari 4 MB',
        ]);

        // Menangani gambar (jika ada)
        if ($image = $request->file('image')) {
            $destinationPath = 'upload/purchases/';
            $originalFileName = $image->getClientOriginalName();
            $imageMimeType = $image->getMimeType();

            // Memastikan file adalah gambar
            if (strpos($imageMimeType, 'image/') === 0) {
                $imageName = date('YmdHis') . '_' . str_replace(' ', '_', $originalFileName);
                $image->move($destinationPath, $imageName);

                $sourceImagePath = public_path($destinationPath . $imageName);
                $webpImagePath = $destinationPath . pathinfo($imageName, PATHINFO_FILENAME) . '.webp';

                // Mengubah gambar ke format webp
                switch ($imageMimeType) {
                    case 'image/jpeg':
                        $sourceImage = @imagecreatefromjpeg($sourceImagePath);
                        break;
                    case 'image/png':
                        $sourceImage = @imagecreatefrompng($sourceImagePath);
                        break;
                    default:
                        throw new \Exception('Tipe MIME tidak didukung.');
                }

                // Jika gambar berhasil dibaca, konversi ke WebP dan hapus gambar asli
                if ($sourceImage !== false) {
                    imagewebp($sourceImage, $webpImagePath);
                    imagedestroy($sourceImage);
                    @unlink($sourceImagePath); // Menghapus file gambar asli
                    $data['image'] = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
                } else {
                    throw new \Exception('Gagal membaca gambar asli.');
                }
            } else {
                throw new \Exception('Tipe MIME gambar tidak didukung.');
            }
        } else {
            $data['image'] = ''; // Jika tidak ada image yang diupload
        }

        // Simpan data pembelian ke dalam database
        $purchase = new Purchase();
        $purchase->image = $data['image'];  // Perbaikan disini, bukan $request->image
        $purchase->type_payment = $request->type_payment;
        $purchase->purchase_date = $request->purchase_date;
        $purchase->no_purchase = $request->no_purchase;
        $purchase->supplier_id = $request->supplier_id;
        $purchase->user_id = Auth::id(); // Ganti dengan field yang sesuai dengan pic
        $purchase->cash_id = $request->cash_id;
        $purchase->total_cost = str_replace(['.', ','], '', $request->total_cost);
        $purchase->status = $request->status;
        $purchase->description = $request->description;
        $purchase->save();

        // Mendapatkan ID dari purchase yang baru saja disimpan
        $purchaseId = $purchase->id;

        // Simpan detail purchase ke dalam database
        $productIds = $request->product_id;
        $quantitys = $request->quantity;
        $purchaseprice = $request->purchase_price;

        foreach ($productIds as $key => $productId) {
            $hargaBeliWithoutSeparator = str_replace(['.', ','], '', $purchaseprice[$key]);
            $detail = new PurchaseItem();
            $detail->purchase_id = $purchaseId;
            $detail->product_id = $productId;
            $detail->purchase_price = $hargaBeliWithoutSeparator;
            $detail->quantity = $quantitys[$key];
            $detail->total_price = $quantitys[$key] * $hargaBeliWithoutSeparator;
            $detail->save();
        }

        // Mengecek saldo cash sebelum melanjutkan transaksi
        $cash = Cash::find($request->cash_id);
        if ($cash && $cash->amount < $request->total_cost) {
            return response()->json([
                'success' => false,
                'message' => 'Saldo cash tidak mencukupi untuk transaksi ini.',
            ], 400); // 400 adalah kode status HTTP untuk permintaan yang salah
        }

        // Proses pembayaran dan pembaruan stok hanya jika status pembelian 'Lunas'
        if ($purchase->status === 'Lunas') {
            // Update stock produk
            foreach ($request->product_id as $key => $productId) {
                $product = Product::find($productId);
                if ($product) {
                    $product->stock += $request->quantity[$key]; // Tambahkan stok
                    $product->save();
                }
            }

            // Update saldo cash berdasarkan cash_id (hanya update saldo, tanpa pengecekan saldo)
            $cash = Cash::find($request->cash_id); // Menemukan data cash berdasarkan cash_id
            if ($cash) {
                $cash->amount -= $purchase->total_cost; // Kurangi saldo cash
                $cash->save();
            } else {
                // Jika cash_id tidak ditemukan, batalkan transaksi dan kirimkan error
                $purchase->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'Cash ID tidak ditemukan. Silakan periksa input data Anda.'
                ], 400);  // 400 adalah kode status HTTP untuk permintaan yang salah
            }

            // Simpan data ke tabel profit_loss jika status adalah Lunas
            $profitLoss = new Profit();
            $profitLoss->cash_id = $request->cash_id;
            $profitLoss->purchase_id = $purchase->id;
            $profitLoss->date = $purchase->purchase_date;
            $profitLoss->category = 'kurang';
            $profitLoss->amount = $purchase->total_cost;
            $profitLoss->save();
        }



        // Mendapatkan ID user yang sedang login
        $loggedInUserId = Auth::id();

        // Simpan log histori untuk operasi Create dengan user_id yang sedang login
        $this->simpanLogHistori('Create', 'Purchase', $purchase->id, $loggedInUserId, null, json_encode($purchase));

        // Kembalikan respons sukses
        return response()->json(['success' => true, 'message' => 'Pembelian berhasil disimpan'], 200);
    }









    /**
     * Display the specified resource.
     */
    // Controller Method (Show Pembelian)
    public function show($id)
    {
        $title = "Halaman Lihat Pembelian";
        $subtitle = "Menu Lihat Pembelian";
        // Ambil data pembelian berdasarkan ID
        $purchase = Purchase::with(['supplier', 'user', 'purchaseItems.product'])->findOrFail($id);

        // Kirim data ke view
        return view('purchase.show', compact('purchase', 'title', 'subtitle'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = "Halaman Lihat Pembelian";
        $subtitle = "Menu Lihat Pembelian";
        // Ambil data pembelian berdasarkan ID
        $purchase = Purchase::with('purchaseItems.product')->findOrFail($id);


        // Ambil data lainnya yang dibutuhkan untuk dropdown
        $data_suppliers = Supplier::all();
        $data_products = Product::all();
        $data_cashes = Cash::all();

        // Kirim data ke view
        return view('purchase.edit', compact('purchase', 'title', 'subtitle', 'data_suppliers', 'data_products', 'data_cashes'));
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, $id)
    // {
    //     // Validasi input dari form
    //     $request->validate([
    //         'purchase_date' => 'required|date',
    //         'image' => 'mimes:jpg,jpeg,png,gif|max:4048',
    //         'items.*.product_id' => 'required|exists:products,id',
    //         'items.*.purchase_price' => 'required|regex:/^\d+([.,]\d+)*$/',
    //         'items.*.quantity' => 'required|integer|min:1',
    //     ], [
    //         'items.*.product_id.required' => 'Produk harus dipilih.',
    //         'items.*.product_id.exists' => 'Produk yang dipilih tidak valid.',
    //         'items.*.purchase_price.required' => 'Harga pembelian harus diisi.',
    //         'items.*.purchase_price.regex' => 'Format harga pembelian tidak valid.',
    //         'items.*.quantity.required' => 'Jumlah harus diisi.',
    //         'items.*.quantity.integer' => 'Jumlah harus berupa angka.',
    //         'items.*.quantity.min' => 'Jumlah minimal adalah 1.',
    //     ]);




    //     // Temukan data pembelian berdasarkan ID
    //     $purchase = Purchase::findOrFail($id);

    //     // Simpan status dan data awal sebelum update
    //     $oldStatus = $purchase->status;
    //     $oldData = $purchase->toArray();

    //     // Menangani gambar (jika ada)
    //     if ($image = $request->file('image')) {
    //         $destinationPath = 'upload/purchases/';
    //         $originalFileName = $image->getClientOriginalName();
    //         $imageMimeType = $image->getMimeType();

    //         // Hapus gambar lama jika ada
    //         if ($purchase->image && file_exists(public_path($destinationPath . $purchase->image))) {
    //             @unlink(public_path($destinationPath . $purchase->image));
    //         }

    //         // Proses upload gambar baru
    //         $imageName = date('YmdHis') . '_' . str_replace(' ', '_', $originalFileName);
    //         $image->move($destinationPath, $imageName);

    //         $sourceImagePath = public_path($destinationPath . $imageName);
    //         $webpImagePath = $destinationPath . pathinfo($imageName, PATHINFO_FILENAME) . '.webp';

    //         // Konversi gambar ke WebP
    //         switch ($imageMimeType) {
    //             case 'image/jpeg':
    //                 $sourceImage = @imagecreatefromjpeg($sourceImagePath);
    //                 break;
    //             case 'image/png':
    //                 $sourceImage = @imagecreatefrompng($sourceImagePath);
    //                 break;
    //             default:
    //                 throw new \Exception('Tipe MIME tidak didukung.');
    //         }

    //         if ($sourceImage !== false) {
    //             imagewebp($sourceImage, $webpImagePath);
    //             imagedestroy($sourceImage);
    //             @unlink($sourceImagePath); // Menghapus file gambar asli
    //             $purchase->image = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
    //         } else {
    //             throw new \Exception('Gagal membaca gambar asli.');
    //         }
    //     }

    //     // Update data pembelian
    //     $purchase->description = $request->description;
    //     $purchase->type_payment = $request->type_payment;
    //     $purchase->purchase_date = $request->purchase_date;
    //     $purchase->status = $request->status;
    //     $purchase->cash_id = $request->cash_id;
    //     $purchase->supplier_id = $request->supplier_id;
    //     $purchase->no_purchase = $request->no_purchase;
    //     $purchase->user_id = Auth::id();
    //     $purchase->total_cost = str_replace(['.', ','], '', $request->total_cost);
    //     $purchase->save();

    //     // Mendapatkan ID dari purchase yang baru saja disimpan
    //     $purchaseId = $purchase->id;

    //     // Update atau simpan detail purchase ke dalam database
    //     $items = $request->items;
    //     foreach ($items as $itemId => $itemData) {
    //         $hargaBeliWithoutSeparator = str_replace(['.', ','], '', $itemData['purchase_price']);
    //         $purchaseItem = PurchaseItem::findOrNew($itemId); // Update jika item ada, buat baru jika tidak ada
    //         $purchaseItem->purchase_id = $purchaseId;
    //         $purchaseItem->product_id = $itemData['product_id'];
    //         $purchaseItem->purchase_price = $hargaBeliWithoutSeparator;
    //         $purchaseItem->quantity = $itemData['quantity'];
    //         $purchaseItem->total_price = $itemData['quantity'] * $hargaBeliWithoutSeparator;
    //         $purchaseItem->save();
    //     }

    //     // Jika status berubah menjadi "Lunas", tambahkan stok dan kurangi saldo kas
    //     if ($oldStatus !== 'Lunas' && $purchase->status === 'Lunas') {
    //         foreach ($items as $itemId => $itemData) {
    //             $purchaseItem = PurchaseItem::findOrFail($itemId);
    //             $product = Product::find($purchaseItem->product_id);

    //             if ($product) {
    //                 $product->stock += $purchaseItem->quantity;
    //                 $product->save();
    //             }
    //         }

    //         // Pemotongan saldo kas
    //         $cash = Cash::find($request->cash_id);
    //         if ($cash) {
    //             if ($cash->amount >= $purchase->total_cost) {
    //                 $cash->amount -= $purchase->total_cost;  // Mengurangi saldo kas
    //                 $cash->save();  // Simpan perubahan kas
    //             } else {
    //                 return response()->json(['error' => 'Saldo cash tidak mencukupi'], 400);
    //             }
    //         } else {
    //             return response()->json(['error' => 'Kas tidak ditemukan'], 404);
    //         }
    //     }

    //     // Simpan log histori
    //     $loggedInUserId = Auth::id();
    //     $this->simpanLogHistori('Update', 'purchases', $purchase->id, $loggedInUserId, json_encode($oldData), json_encode($purchase->toArray()));

    //     // Return response JSON
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Pembelian berhasil diperbarui!',
    //     ]);
    // }

    public function update(Request $request, string $id)
    {
        // Validasi data
        $request->validate([
            'purchase_date' => 'required|date',
            'product_id' => 'required|array',
            'quantity' => 'required|array',
            'purchase_price' => 'required|array',
            'total_cost' => 'required|numeric',
            'status' => 'required', // Pastikan status juga divalidasi
            'description' => 'nullable|string',
            'type_payment' => 'nullable|string',
            'no_purchase' => 'nullable|string',
            'cash_id' => 'nullable|exists:cash,id', // Pastikan cash_id valid
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Validasi untuk gambar
        ]);

        // Cari pembelian
        $purchase = Purchase::findOrFail($id);
        // Simpan data lama sebelum diupdate
        $oldData = $purchase->toArray();

        // Proses upload gambar jika ada
        if ($image = $request->file('image')) {
            $destinationPath = 'upload/purchases/';
            $originalFileName = $image->getClientOriginalName();
            $imageMimeType = $image->getMimeType();

            // Hapus gambar lama jika ada
            if ($purchase->image && file_exists(public_path($destinationPath . $purchase->image))) {
                @unlink(public_path($destinationPath . $purchase->image));
            }

            // Proses upload gambar baru
            $imageName = date('YmdHis') . '_' . str_replace(' ', '_', $originalFileName);
            $image->move(public_path($destinationPath), $imageName);

            // Path gambar yang telah diupload
            $sourceImagePath = public_path($destinationPath . $imageName);
            $webpImagePath = $destinationPath . pathinfo($imageName, PATHINFO_FILENAME) . '.webp';

            // Konversi gambar ke WebP
            switch ($imageMimeType) {
                case 'image/jpeg':
                    $sourceImage = @imagecreatefromjpeg($sourceImagePath);
                    break;
                case 'image/png':
                    $sourceImage = @imagecreatefrompng($sourceImagePath);
                    break;
                default:
                    throw new \Exception('Tipe MIME tidak didukung.');
            }

            // Jika gambar berhasil dibaca, konversi ke WebP
            if ($sourceImage !== false) {
                imagewebp($sourceImage, public_path($webpImagePath));
                imagedestroy($sourceImage);
                @unlink($sourceImagePath); // Menghapus file gambar asli
                $purchase->image = pathinfo($imageName, PATHINFO_FILENAME) . '.webp'; // Update nama gambar di database
            } else {
                throw new \Exception('Gagal membaca gambar asli.');
            }
        }

        // Ambil status untuk pengecekan
        $status = $request->status;

        // Jika status "Lunas", lakukan pengurangan saldo kas dan penambahan stok produk
        if ($status === 'Lunas') {
            $cashId = $request->cash_id;
            $cashAmount = Cash::find($cashId)->amount; // Ambil jumlah saldo kas yang dipilih

            // Validasi jika saldo kas tidak mencukupi
            if ($purchase->total_cost > $cashAmount) {
                return response()->json(['success' => false, 'message' => 'Saldo Kas tidak mencukupi.']);
            }

            // Kurangi saldo kas sesuai dengan total biaya pembelian
            $newCashAmount = $cashAmount - $purchase->total_cost;
            Cash::find($cashId)->update(['amount' => $newCashAmount]); // Update saldo kas

            // Proses penambahan stok produk
            foreach ($request->product_id as $key => $productId) {
                $quantity = $request->quantity[$key];
                $product = Product::find($productId);

                if ($product) {
                    // Tambah stok produk
                    $product->increment('stock', $quantity);
                }
            }
        }

        // Update data pembelian
        $purchase->update([
            'description' => $request->description,
            'type_payment' => $request->type_payment,
            'purchase_date' => $request->purchase_date,
            'status' => $request->status,
            'cash_id' => $request->cash_id,
            'supplier_id' => $request->supplier_id,
            'no_purchase' => $request->no_purchase,
            'user_id' => Auth::id(),
            'total_cost' => str_replace(['.', ','], '', $request->total_cost),
        ]);

        // Ambil semua `product_id` dari request
        $newProductIds = $request->product_id;

        // Ambil semua item lama dari database
        $existingItems = $purchase->purchaseItems()->get();

        // Inisialisasi untuk menghitung total biaya pembelian
        $total_cost = 0;

        // Proses update dan tambah item
        foreach ($newProductIds as $key => $productId) {
            $quantity = $request->quantity[$key];
            $purchase_price = str_replace(['.', ','], '', $request->purchase_price[$key]);

            // Hitung total price untuk setiap item pembelian
            $total_price = $quantity * $purchase_price;

            // Periksa apakah item ini sudah ada di database
            $existingItem = $existingItems->firstWhere('product_id', $productId);

            if ($existingItem) {
                // Jika item sudah ada, perbarui
                $existingItem->update([
                    'quantity' => $quantity,
                    'purchase_price' => $purchase_price,
                    'total_price' => $total_price,
                ]);
            } else {
                // Jika item belum ada, tambahkan baru
                PurchaseItem::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'purchase_price' => $purchase_price,
                    'total_price' => $total_price,
                ]);
            }

            // Tambahkan total_price ke total cost pembelian
            $total_cost += $total_price;
        }

        // Hapus item yang tidak ada di request tetapi ada di database
        $existingItems->whereNotIn('product_id', $newProductIds)->each(function ($item) {
            $item->delete();
        });

        // Update total cost pembelian setelah semua item diproses
        $purchase->update(['total_cost' => $total_cost]);

        $newData = $purchase->refresh()->toArray();



         // Simpan ke tabel profit_loss jika status adalah "Lunas"
         if ($purchase->status === 'Lunas') {
            Profit::updateOrCreate(
                ['purchase_id' => $purchase->id],
                [
                    'cash_id' => $purchase->cash_id,
                    'date' => $purchase->purchase_date,
                    'category' => 'kurang',
                    'amount' => $purchase->total_cost,
                ]
            );
        }

        // Simpan log histori
        $loggedInUserId = Auth::id();
        $this->simpanLogHistori(
            'Update',
            'Purchase',
            $purchase->id,
            $loggedInUserId,
            json_encode($oldData),
            json_encode($newData)
        );

        return response()->json(['success' => true, 'message' => 'Pembelian berhasil diperbarui.']);
    }






    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Ambil data purchase yang akan dihapus
        $purchase = Purchase::findOrFail($id);

        // Mulai transaksi untuk memastikan semuanya atau tidak sama sekali
        DB::beginTransaction();

        try {
            // Cek status pembelian, hanya jika status "Lunas" yang mempengaruhi stok dan cash
            if ($purchase->status == 'Lunas') {
                // Perbarui stok produk berdasarkan purchase_items yang ada
                foreach ($purchase->purchaseItems as $item) {
                    // Mengurangi stok produk berdasarkan quantity yang dibeli
                    $product = $item->product;
                    $product->stock -= $item->quantity; // Mengurangi stok produk
                    $product->save();
                }

                // Menambah kembali jumlah cash yang digunakan dalam purchase
                $cash = $purchase->cash;
                $cash->amount += $purchase->total_cost; // Menambah cash yang digunakan
                $cash->save();
            }

            // Hapus gambar terkait pembelian jika ada
            if ($purchase->image) {
                $imagePath = public_path('upload/purchases/' . $purchase->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath); // Menghapus file gambar
                }
            }

            // Hapus semua purchase_items terkait
            $purchase->purchaseItems()->delete();

            // Hapus purchase
            $purchase->delete();

            // Commit transaksi
            DB::commit();

            // Mendapatkan ID pengguna yang sedang login
            $loggedInUserId = Auth::id();

            // Simpan log histori untuk operasi Delete
            $this->simpanLogHistori('Delete', 'Purchase', $id, $loggedInUserId, json_encode($purchase), null);

            return redirect()->route('purchases.index')->with('success', 'Pembelian berhasil dihapus dan data terkait telah diperbarui.');
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollback();

            // Log error jika diperlukan
            Log::error("Error menghapus pembelian: " . $e->getMessage());

            return back()->with('error', 'Terjadi kesalahan saat menghapus pembelian.');
        }
    }
}
