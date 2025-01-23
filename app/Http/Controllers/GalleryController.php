<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\Gallery;

 
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
 

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:gallery-list|gallery-create|gallery-edit|gallery-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:gallery-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:gallery-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:gallery-delete', ['only' => ['destroy']]);
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
        $title = "Halaman Galeri";
        $subtitle = "Menu Galeri";

        // Ambil data untuk dropdown select
        $data_galleries = Gallery::all();


        // Kirim semua data ke view
        return view('gallery.index', compact('data_galleries', 'title', 'subtitle'));
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Galeri";
        $subtitle = "Menu Tambah Galeri";


        // Kirim data ke view
        return view('gallery.create', compact('title', 'subtitle'));
    }



    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => 'required',

            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:4096',
        ], [
            'name.required' => 'Nama wajib diisi.',

            'image.image' => 'Gambar harus dalam format jpeg, jpg, atau png',
            'image.mimes' => 'Format gambar harus jpeg, jpg, atau png',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 4 MB',
        ]);



        $input = $request->all();

        if ($image = $request->file('image')) {
            $destinationPath = 'upload/galleries/';

            // Mengambil nama file asli dan ekstensinya
            $originalFileName = $image->getClientOriginalName();

            // Membaca tipe MIME dari file image
            $imageMimeType = $image->getMimeType();

            // Menyaring hanya tipe MIME image yang didukung (misalnya, image/jpeg, image/png, dll.)
            if (strpos($imageMimeType, 'image/') === 0) {
                // Menggabungkan waktu dengan nama file asli
                $imageName = date('YmdHis') . '_' . str_replace(' ', '_', $originalFileName);

                // Simpan image asli ke tujuan yang diinginkan
                $image->move($destinationPath, $imageName);

                // Path image asli
                $sourceImagePath = public_path($destinationPath . $imageName);

                // Path untuk menyimpan image WebP
                $webpImagePath = $destinationPath . pathinfo($imageName, PATHINFO_FILENAME) . '.webp';

                // Membaca image asli dan mengonversinya ke WebP jika tipe MIME-nya didukung
                switch ($imageMimeType) {
                    case 'image/jpeg':
                        $sourceImage = @imagecreatefromjpeg($sourceImagePath);
                        break;
                    case 'image/png':
                        $sourceImage = @imagecreatefrompng($sourceImagePath);
                        break;
                        // Tambahkan jenis MIME lain jika diperlukan
                    default:
                        // Jenis MIME tidak didukung, tangani kasus ini sesuai kebutuhan Anda
                        // Misalnya, tampilkan pesan kesalahan atau lakukan tindakan yang sesuai
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
                } else {
                    // Gagal membaca image asli, tangani kasus ini sesuai kebutuhan Anda
                }
            } else {
                // Tipe MIME image tidak didukung, tangani kasus ini sesuai kebutuhan Anda
            }
        } else {
            // Set nilai default untuk image jika tidak ada image yang diunggah
            $input['image'] = '';
        }

        // Membuat gallery baru dan mendapatkan data pengguna yang baru dibuat
        $gallery = Gallery::create($input);

        // Simpan log histori
        $loggedInUserId = Auth::id();
        $this->simpanLogHistori('Create', 'Gallery', $gallery->id, $loggedInUserId, null, json_encode($gallery));

        return redirect()->route('galleries.index')->with('success', 'Data berhasil disimpan');
    }










    /**
     * Display the specified resource.
     *
     * @param  \App\Gallerys  $gallery
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // Judul untuk halaman
        $title = "Halaman Lihat Galeri";
        $subtitle = "Menu Lihat Galeri";



        // Ambil data gallery berdasarkan ID
        $data_galleries = Gallery::findOrFail($id);

        // Kembalikan view dengan membawa data produk
        return view('gallery.show', compact(
            'title',
            'subtitle',

            'data_galleries',
        ));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Gallerys  $gallery
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Galeri";
        $subtitle = "Menu Edit Galeri";


        // Ambil data gallery berdasarkan ID
        $data_galleries = Gallery::findOrFail($id);

        // Kirim data ke view
        return view('gallery.edit', compact(
            'title',
            'subtitle',

            'data_galleries',
        ));
    }


    public function update(Request $request, Gallery $gallery): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:4096',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'image.image' => 'Gambar harus dalam format jpeg, jpg, atau png',
            'image.mimes' => 'Format gambar harus jpeg, jpg, atau png',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 4 MB',
        ]);
    
        // Simpan data lama untuk log
        $oldData = $gallery->toArray();
        
        $input = $request->all();
    
        if ($image = $request->file('image')) {
            $destinationPath = 'upload/galleries/';
    
            // Hapus gambar lama jika ada
            if ($gallery->image && file_exists(public_path($destinationPath . $gallery->image))) {
                unlink(public_path($destinationPath . $gallery->image));
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
            $input['image'] = $gallery->image;
        }
    
        // Update data gallery
        $gallery->update($input);
    
        // Simpan log histori
        $loggedInUserId = Auth::id();
        $this->simpanLogHistori(
            'Update',
            'Gallery',
            $gallery->id,
            $loggedInUserId,
            json_encode($oldData),
            json_encode($gallery->toArray())
        );
    
        return redirect()->route('galleries.index')->with('success', 'Data berhasil diperbarui');
    }








    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Gallerys  $gallery
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Cari gallery berdasarkan ID
        $gallery = Gallery::findOrFail($id);

      
        try {


            // Hapus file gambar jika ada
            if (!empty($gallery->image)) {
                $imagePath = public_path('upload/galleries/' . $gallery->image);
                if (file_exists($imagePath)) {
                    @unlink($imagePath); // Menghapus file gambar
                }
            }



            // Hapus gallery dari tabel galleries
            $gallery->delete();

            // Mendapatkan ID pengguna yang sedang login
            $loggedInUserId = Auth::id();

            // Simpan log histori untuk operasi Delete
            $this->simpanLogHistori('Delete', 'Gallery', $id, $loggedInUserId, json_encode($gallery), null);

            // Commit gallery
            DB::commit();

            // Redirect kembali dengan pesan sukses
            return redirect()->route('galleries.index')->with('success', 'Gallery berhasil dihapus');
        } catch (\Exception $e) {
            // Rollback gallery jika terjadi error
            DB::rollBack();

            // Kembalikan pesan error
            return redirect()->route('galleries.index')->with('error', 'Gagal menghapus gallery: ' . $e->getMessage());
        }
    }
}
