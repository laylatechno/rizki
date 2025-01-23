<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\BlogCategory;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
 

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:blog_category-list|blog_category-create|blog_category-edit|blog_category-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:blog_category-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:blog_category-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:blog_category-delete', ['only' => ['destroy']]);
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
        $title = "Halaman Kategori Blog";
        $subtitle = "Menu Kategori Blog";

        // Ambil data untuk dropdown select
        $data_blog_categories = BlogCategory::all();


        // Kirim semua data ke view
        return view('blog_category.index', compact('data_blog_categories', 'title', 'subtitle'));
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Kategori Blog";
        $subtitle = "Menu Tambah Kategori Blog";
        

        // Kirim data ke view
        return view('blog_category.create', compact('title', 'subtitle','data_blog_categories'));
    }



    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|integer|min:0',
            'image' => 'image|mimes:jpeg,jpg,png|max:4096',
        ], [
            'name.required' => 'Nama Kategori Blog wajib diisi.',
            'name.string' => 'Nama Kategori Blog harus berupa teks.',
            'name.max' => 'Nama Kategori Blog tidak boleh lebih dari 255 karakter.',
     
    
            'position.integer' => 'Urutan harus berupa angka.',
            'position.min' => 'Urutan tidak boleh kurang dari 0.',
    
            'image.image' => 'Gambar harus berupa file gambar.',
            'image.mimes' => 'Format gambar harus jpeg, jpg, atau png.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 4 MB.',
        ]);
    
        // Mengambil semua input dan memproses harga
        $input = $request->all();
    
   
    
        if ($image = $request->file('image')) {
            $destinationPath = 'upload/blog_categories/';
    
            $originalFileName = $image->getClientOriginalName();
            $imageMimeType = $image->getMimeType();
    
            if (strpos($imageMimeType, 'image/') === 0) {
                $imageName = date('YmdHis') . '_' . str_replace(' ', '_', $originalFileName);
                $image->move($destinationPath, $imageName);
    
                $sourceImagePath = public_path($destinationPath . $imageName);
                $webpImagePath = $destinationPath . pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
    
                switch ($imageMimeType) {
                    case 'image/jpeg':
                        $sourceImage = @imagecreatefromjpeg($sourceImagePath);
                        break;
                    case 'image/png':
                        $sourceImage = @imagecreatefrompng($sourceImagePath);
                        break;
                    default:
                        $sourceImage = false;
                        break;
                }
    
                if ($sourceImage !== false) {
                    imagewebp($sourceImage, $webpImagePath);
                    imagedestroy($sourceImage);
                    @unlink($sourceImagePath);
    
                    $input['image'] = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
                }
            }
        } else {
            $input['image'] = '';
        }
    
        $blog_category = BlogCategory::create($input);
    
        $loggedInUserId = Auth::id();
        $this->simpanLogHistori('Create', 'Blog Category', $blog_category->id, $loggedInUserId, null, json_encode($blog_category));
    
        return redirect()->route('blog_categories.index')->with('success', 'Data berhasil disimpan');
    }
    










    /**
     * Display the specified resource.
     *
     * @param  \App\BlogCategorys  $blog_category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // Judul untuk halaman
        $title = "Halaman Lihat BlogCategory";
        $subtitle = "Menu Lihat BlogCategory";



        // Ambil data blog_category berdasarkan ID
        $data_blog_categories = BlogCategory::findOrFail($id);

        // Kembalikan view dengan membawa data produk
        return view('blog_category.show', compact(
            'title',
            'subtitle',

            'data_blog_categories',
        ));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BlogCategorys  $blog_category
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Blog Category";
        $subtitle = "Menu Edit Blog Category";


        // Ambil data blog_category berdasarkan ID
        $data_blog_categories = BlogCategory::findOrFail($id);

        // Kirim data ke view
        return view('blog_category.edit', compact(
            'title',
            'subtitle',
            'data_blog_categories',
        ));
    }


    public function update(Request $request, BlogCategory $blog_category): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'nullable|integer|min:0',
            'image' => 'image|mimes:jpeg,jpg,png|max:4096',
        ], [
            'name.required' => 'Nama Kategori Blog wajib diisi.',
            'name.string' => 'Nama Kategori Blog harus berupa teks.',
            'name.max' => 'Nama Kategori Blog tidak boleh lebih dari 255 karakter.',
    
            
     
    
            'position.integer' => 'Urutan harus berupa angka.',
            'position.min' => 'Urutan tidak boleh kurang dari 0.',
    
            'image.image' => 'Gambar harus berupa file gambar.',
            'image.mimes' => 'Format gambar harus jpeg, jpg, atau png.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 4 MB.',
        ]);
    
        // Simpan data lama untuk log
        $oldData = $blog_category->toArray();
    
        $input = $request->all();
     
    
        if ($image = $request->file('image')) {
            $destinationPath = 'upload/blog_categories/';
    
            // Hapus gambar lama jika ada
            if ($blog_category->image && file_exists(public_path($destinationPath . $blog_category->image))) {
                unlink(public_path($destinationPath . $blog_category->image));
            }
    
            // Mengambil nama file asli dan ekstensinya
            $originalFileName = $image->getClientOriginalName();
    
            // Membaca tipe MIME dari file image
            $imageMimeType = $image->getMimeType();
    
            // Menyaring hanya tipe MIME image yang didukung
            if (strpos($imageMimeType, 'image/') === 0) {
                // Menggabungkan waktu dengan nama file asli
                $imageName = date('YmdHis') . '_' . str_replace(' ', '_', $originalFileName);
    
                // Simpan image asli ke tujuan yang diinginkan
                $image->move($destinationPath, $imageName);
    
                // Path image asli
                $sourceImagePath = public_path($destinationPath . $imageName);
    
                // Path untuk menyimpan image WebP
                $webpImagePath = $destinationPath . pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
    
                // Membaca image asli dan mengonversinya ke WebP
                $sourceImage = null;
                switch ($imageMimeType) {
                    case 'image/jpeg':
                        $sourceImage = @imagecreatefromjpeg($sourceImagePath);
                        break;
                    case 'image/png':
                        $sourceImage = @imagecreatefrompng($sourceImagePath);
                        break;
                    default:
                        break;
                }
    
                // Jika image asli berhasil dibaca
                if ($sourceImage !== false) {
                    // Membuat image baru dalam format WebP
                    imagewebp($sourceImage, $webpImagePath);
    
                    // Hapus image asli dari memori
                    imagedestroy($sourceImage);
    
                    // Hapus file asli setelah konversi selesai
                    @unlink($sourceImagePath);
    
                    // Simpan hanya nama file image ke dalam array input
                    $input['image'] = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
                }
            }
        } else {
            // Jika tidak ada upload image baru, gunakan image yang ada
            $input['image'] = $blog_category->image;
        }
    
        // Update data blog_category
        $blog_category->update($input);
    
        // Simpan log histori
        $loggedInUserId = Auth::id();
        $this->simpanLogHistori(
            'Update',
            'Blog Category',
            $blog_category->id,
            $loggedInUserId,
            json_encode($oldData),
            json_encode($blog_category->toArray())
        );
    
        return redirect()->route('blog_categories.index')->with('success', 'Data berhasil diperbarui');
    }
    








    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BlogCategorys  $blog_category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Cari blog_category berdasarkan ID
        $blog_category = BlogCategory::findOrFail($id);

      
        try {


            // Hapus file gambar jika ada
            if (!empty($blog_category->image)) {
                $imagePath = public_path('upload/blog_categories/' . $blog_category->image);
                if (file_exists($imagePath)) {
                    @unlink($imagePath); // Menghapus file gambar
                }
            }



            // Hapus blog_category dari tabel blog_categories
            $blog_category->delete();

            // Mendapatkan ID pengguna yang sedang login
            $loggedInUserId = Auth::id();

            // Simpan log histori untuk operasi Delete
            $this->simpanLogHistori('Delete', 'Blog Category', $id, $loggedInUserId, json_encode($blog_category), null);

            // Commit blog_category
            DB::commit();

            // Redirect kembali dengan pesan sukses
            return redirect()->route('blog_categories.index')->with('success', 'Blog Category berhasil dihapus');
        } catch (\Exception $e) {
            // Rollback blog_category jika terjadi error
            DB::rollBack();

            // Kembalikan pesan error
            return redirect()->route('blog_categories.index')->with('error', 'Gagal menghapus blog_category: ' . $e->getMessage());
        }
    }
}
