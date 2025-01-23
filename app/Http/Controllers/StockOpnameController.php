<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\StockOpname;
use App\Models\Category;
use App\Models\CustomerCategory;
use App\Models\Product;
use App\Models\StockOpnameDetail;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;



class StockOpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:stockopname-list|stockopname-create|stockopname-edit|stockopname-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:stockopname-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:stockopname-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:stockopname-delete', ['only' => ['destroy']]);
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


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $title = "Halaman Stock Opname";
        $subtitle = "Menu Stock Opname";

        // Ambil data hanya yang diperlukan untuk dropdown dan tabel
        $data_stock_opname = StockOpname::all(); // Ambil hanya kolom penting



        return view('stock_opname.index', compact('data_stock_opname', 'title', 'subtitle'));
    }







    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Stock Opname";
        $subtitle = "Menu Tambah Stock Opname";

        // Ambil data untuk dropdown select
        $data_products = Product::all();


        // Kirim data ke view
        return view('stock_opname.create', compact('title', 'subtitle', 'data_products'));
    }




    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */






    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'opname_date' => 'required|date', // Tanggal opname wajib diisi dan harus berupa format tanggal yang valid
            'description' => 'nullable|string', // Deskripsi bersifat opsional dan jika diisi, harus berupa string
            'products' => 'required|array', // Produk wajib diisi dalam bentuk array
            'products.*.product_id' => 'required|exists:products,id', // Setiap produk harus memiliki product_id yang valid dan ada di tabel products
            'products.*.system_stock' => 'required|integer|min:0', // Setiap produk harus memiliki system_stock yang valid, berupa angka bulat dan minimal 0
            'products.*.physical_stock' => 'required|integer|min:0', // Setiap produk harus memiliki physical_stock yang valid, berupa angka bulat dan minimal 0
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:4048', // Gambar bersifat opsional, jika diunggah harus memiliki ekstensi JPG, JPEG, PNG, atau GIF, dan ukuran maksimal 4 MB
        ], [
            // Pesan error kustom
            'opname_date.required' => 'Tanggal opname wajib diisi.', // Pesan error jika tanggal opname tidak diisi
            'opname_date.date' => 'Tanggal opname harus berupa format tanggal yang valid.', // Pesan error jika format tanggal tidak valid

            'description.string' => 'Deskripsi harus berupa teks.', // Pesan error jika deskripsi bukan string

            'products.required' => 'Produk wajib dipilih.', // Pesan error jika produk tidak dipilih
            'products.array' => 'Produk harus dalam bentuk array.', // Pesan error jika produk bukan array

            'products.*.product_id.required' => 'ID produk wajib diisi.', // Pesan error jika product_id tidak diisi
            'products.*.product_id.exists' => 'Produk yang dipilih tidak valid.', // Pesan error jika product_id tidak ada di tabel products

            'products.*.system_stock.required' => 'Stok sistem wajib diisi.', // Pesan error jika system_stock tidak diisi
            'products.*.system_stock.integer' => 'Stok sistem harus berupa angka.', // Pesan error jika system_stock bukan angka
            'products.*.system_stock.min' => 'Stok sistem tidak boleh kurang dari 0.', // Pesan error jika system_stock kurang dari 0

            'products.*.physical_stock.required' => 'Stok fisik wajib diisi.', // Pesan error jika physical_stock tidak diisi
            'products.*.physical_stock.integer' => 'Stok fisik harus berupa angka.', // Pesan error jika physical_stock bukan angka
            'products.*.physical_stock.min' => 'Stok fisik tidak boleh kurang dari 0.', // Pesan error jika physical_stock kurang dari 0

            'image.mimes' => 'Bukti yang dimasukkan hanya diperbolehkan berekstensi JPG, JPEG, PNG, dan GIF.', // Pesan error jika ekstensi gambar tidak sesuai
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 4 MB.', // Pesan error jika ukuran gambar lebih dari 4 MB
        ]);


        $data = $request->only(['opname_date', 'description']);

        // Menangani gambar (jika ada)
        if ($image = $request->file('image')) {
            $destinationPath = 'upload/stock_opname/';
            $originalFileName = $image->getClientOriginalName();
            $imageMimeType = $image->getMimeType();

            // Pastikan file adalah gambar
            if (strpos($imageMimeType, 'image/') === 0) {
                $imageName = date('YmdHis') . '_' . str_replace(' ', '_', $originalFileName);
                $image->move($destinationPath, $imageName);

                $sourceImagePath = public_path($destinationPath . $imageName);
                $webpImagePath = $destinationPath . pathinfo($imageName, PATHINFO_FILENAME) . '.webp';

                // Konversi ke format WebP
                switch ($imageMimeType) {
                    case 'image/jpeg':
                        $sourceImage = imagecreatefromjpeg($sourceImagePath);
                        break;
                    case 'image/png':
                        $sourceImage = imagecreatefrompng($sourceImagePath);
                        break;
                    default:
                        throw new \Exception('Tipe MIME gambar tidak didukung.');
                }

                // Konversi ke WebP dan hapus gambar asli
                if ($sourceImage !== false) {
                    imagewebp($sourceImage, $webpImagePath);
                    imagedestroy($sourceImage);
                    @unlink($sourceImagePath); // Hapus gambar asli
                    $data['image'] = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
                } else {
                    throw new \Exception('Gagal membaca gambar asli.');
                }
            }
        }

        // Buat Stock Opname
        $stockOpname = StockOpname::create([
            'opname_number' => 'SO-' . time(),
            'opname_date' => $data['opname_date'],
            'description' => $data['description'],
            'image' => $data['image'] ?? null, // Tambahkan field image
        ]);

        // Tambahkan detail produk
        foreach ($request->products as $product) {
            if (!isset($product['product_id'])) {
                continue; // Lewati jika produk tidak dipilih
            }

            StockOpnameDetail::create([
                'stock_opname_id' => $stockOpname->id,
                'product_id' => $product['product_id'],
                'system_stock' => $product['system_stock'],
                'physical_stock' => $product['physical_stock'],
                'difference' => $product['physical_stock'] - $product['system_stock'],
                'description_detail' => $product['description_detail'] ?? null,
            ]);
        }

        return redirect()->route('stock_opname.index')->with('success', 'Stock Opname berhasil disimpan.');
    }




    /**
     * Display the specified resource.
     *
     * @param  \App\StockOpnames  $stock_opname
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Ambil data stock opname yang sedang diedit
        $data_stock_opname = StockOpname::with('stockOpnameDetails.product')->findOrFail($id);

        // Ambil data produk yang tersedia
        $data_products = Product::all();

        // Judul untuk halaman
        $title = "Halaman Lihat Stock Opname";
        $subtitle = "Menu Lihat Stock Opname";

        // Kembalikan view dengan membawa data produk
        return view('stock_opname.show', compact(
            'data_stock_opname',
            'title',
            'subtitle',
            'data_products'
        ));
    }

    public function print($id)
    {
           // Judul untuk halaman
           $title = "Halaman Lihat Stock Opname";
           $subtitle = "Menu Lihat Stock Opname";
        $data_stock_opname = StockOpname::findOrFail($id);
        $data_products = Product::all(); // Ambil data produk terkait

        return view('stock_opname.print', compact('data_stock_opname', 'data_products','title','subtitle'));
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StockOpnames  $stock_opname
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Stock Opname";
        $subtitle = "Menu Edit Stock Opname";

        // Ambil data stock opname yang sedang diedit
        $data_stock_opname = StockOpname::with('stockOpnameDetails.product')->findOrFail($id);

        // Ambil data produk yang tersedia
        $data_products = Product::all();

        // Kirim data ke view
        return view('stock_opname.edit', compact(
            'data_stock_opname',
            'title',
            'subtitle',
            'data_products'
        ));
    }







    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\StockOpnames  $stock_opname
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'opname_date' => 'required|date', // Tanggal opname wajib diisi dan harus berupa format tanggal yang valid
            'description' => 'nullable|string', // Deskripsi bersifat opsional dan jika diisi, harus berupa string
            'products' => 'required|array', // Produk wajib diisi dalam bentuk array
            'products.*.product_id' => 'required|exists:products,id', // Setiap produk harus memiliki product_id yang valid dan ada di tabel products
            'products.*.system_stock' => 'required|integer|min:0', // Setiap produk harus memiliki system_stock yang valid, berupa angka bulat dan minimal 0
            'products.*.physical_stock' => 'required|integer|min:0', // Setiap produk harus memiliki physical_stock yang valid, berupa angka bulat dan minimal 0
            'image' => 'nullable|mimes:jpg,jpeg,png,gif|max:4048', // Gambar bersifat opsional, jika diunggah harus memiliki ekstensi JPG, JPEG, PNG, atau GIF, dan ukuran maksimal 4 MB
        ], [
            // Pesan error kustom
            'opname_date.required' => 'Tanggal opname wajib diisi.',
            'opname_date.date' => 'Tanggal opname harus berupa format tanggal yang valid.',
            'description.string' => 'Deskripsi harus berupa teks.',
            'products.required' => 'Produk wajib dipilih.',
            'products.array' => 'Produk harus dalam bentuk array.',
            'products.*.product_id.required' => 'ID produk wajib diisi.',
            'products.*.product_id.exists' => 'Produk yang dipilih tidak valid.',
            'products.*.system_stock.required' => 'Stok sistem wajib diisi.',
            'products.*.system_stock.integer' => 'Stok sistem harus berupa angka.',
            'products.*.system_stock.min' => 'Stok sistem tidak boleh kurang dari 0.',
            'products.*.physical_stock.required' => 'Stok fisik wajib diisi.',
            'products.*.physical_stock.integer' => 'Stok fisik harus berupa angka.',
            'products.*.physical_stock.min' => 'Stok fisik tidak boleh kurang dari 0.',
            'image.mimes' => 'Bukti yang dimasukkan hanya diperbolehkan berekstensi JPG, JPEG, PNG, dan GIF.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 4 MB.',
        ]);

        // Temukan data stock opname berdasarkan ID
        $data_stock_opname = StockOpname::findOrFail($id);

        // Update data stock opname
        $data = $request->only(['opname_date', 'description']);
        $data_stock_opname->update($data);

        // Menangani gambar (jika ada)
        if ($image = $request->file('image')) {
            $destinationPath = 'upload/stock_opname/';
            $originalFileName = $image->getClientOriginalName();
            $imageMimeType = $image->getMimeType();

            // Pastikan file adalah gambar
            if (strpos($imageMimeType, 'image/') === 0) {
                $imageName = date('YmdHis') . '_' . str_replace(' ', '_', $originalFileName);
                $image->move($destinationPath, $imageName);

                $sourceImagePath = public_path($destinationPath . $imageName);
                $webpImagePath = $destinationPath . pathinfo($imageName, PATHINFO_FILENAME) . '.webp';

                // Konversi ke format WebP
                switch ($imageMimeType) {
                    case 'image/jpeg':
                        $sourceImage = imagecreatefromjpeg($sourceImagePath);
                        break;
                    case 'image/png':
                        $sourceImage = imagecreatefrompng($sourceImagePath);
                        break;
                    default:
                        throw new \Exception('Tipe MIME gambar tidak didukung.');
                }

                // Konversi ke WebP dan hapus file asli
                imagewebp($sourceImage, public_path($webpImagePath));
                imagedestroy($sourceImage);
                unlink($sourceImagePath); // Hapus gambar asli jika sudah diubah

                // Simpan nama gambar WebP di database
                $data_stock_opname->image = pathinfo($webpImagePath, PATHINFO_FILENAME) . '.webp';
                $data_stock_opname->save();
            }
        }

        // Menangani produk (update atau insert produk terkait)
        foreach ($request->input('products') as $productData) {
            $productDetail = $data_stock_opname->stockOpnameDetails()->updateOrCreate(
                ['product_id' => $productData['product_id']],
                [
                    'system_stock' => $productData['system_stock'],
                    'physical_stock' => $productData['physical_stock'],
                    'difference' => $productData['physical_stock'] - $productData['system_stock'],
                    'description_detail' => $productData['description_detail']
                ]
            );
        }

        return redirect()->route('stock_opname.index')->with('success', 'Stock Opname berhasil diperbarui.');
    }







    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StockOpnames  $stock_opname
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Cari StockOpname berdasarkan ID
        $stock_opname = StockOpname::findOrFail($id);

        // Mulai transaksi database untuk memastikan konsistensi
        DB::beginTransaction();
        try {
            // Hapus file gambar jika ada
            if (!empty($stock_opname->image)) {
                $imagePath = public_path('upload/stock_opname/' . $stock_opname->image);
                if (file_exists($imagePath)) {
                    if (!unlink($imagePath)) {
                        // Jika gambar gagal dihapus, lemparkan exception
                        throw new \Exception('Gagal menghapus gambar');
                    }
                }
            }

            // Hapus data StockOpname
            $stock_opname->delete();

            // Mendapatkan ID pengguna yang sedang login
            $loggedInUserId = Auth::id();

            // Simpan log histori untuk operasi Delete
            $this->simpanLogHistori('Delete', 'Stock Opname', $id, $loggedInUserId, json_encode($stock_opname), null);

            // Commit transaksi
            DB::commit();

            // Redirect kembali dengan pesan sukses
            return redirect()->route('stock_opname.index')->with('success', 'Stock Opname berhasil dihapus');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();

            // Kembalikan pesan error
            return redirect()->route('stock_opname.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
