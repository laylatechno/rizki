<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\Slider;

 
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
 

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:slider-list|slider-create|slider-edit|slider-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:slider-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:slider-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:slider-delete', ['only' => ['destroy']]);
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
        $title = "Halaman Slider";
        $subtitle = "Menu Slider";

        // Ambil data untuk dropdown select
        $data_sliders = Slider::all();


        // Kirim semua data ke view
        return view('slider.index', compact('data_sliders', 'title', 'subtitle'));
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Slider";
        $subtitle = "Menu Tambah Slider";


        // Kirim data ke view
        return view('slider.create', compact('title', 'subtitle'));
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
            $destinationPath = 'upload/sliders/';

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

        // Membuat slider baru dan mendapatkan data pengguna yang baru dibuat
        $slider = Slider::create($input);

        // Simpan log histori
        $loggedInUserId = Auth::id();
        $this->simpanLogHistori('Create', 'Slider', $slider->id, $loggedInUserId, null, json_encode($slider));

        return redirect()->route('sliders.index')->with('success', 'Data berhasil disimpan');
    }










    /**
     * Display the specified resource.
     *
     * @param  \App\Sliders  $slider
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // Judul untuk halaman
        $title = "Halaman Lihat Slider";
        $subtitle = "Menu Lihat Slider";



        // Ambil data slider berdasarkan ID
        $data_sliders = Slider::findOrFail($id);

        // Kembalikan view dengan membawa data produk
        return view('slider.show', compact(
            'title',
            'subtitle',

            'data_sliders',
        ));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Sliders  $slider
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Slider";
        $subtitle = "Menu Edit Slider";


        // Ambil data slider berdasarkan ID
        $data_sliders = Slider::findOrFail($id);

        // Kirim data ke view
        return view('slider.edit', compact(
            'title',
            'subtitle',

            'data_sliders',
        ));
    }


    public function update(Request $request, Slider $slider): RedirectResponse
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
        $oldData = $slider->toArray();
        
        $input = $request->all();
    
        if ($image = $request->file('image')) {
            $destinationPath = 'upload/sliders/';
    
            // Hapus gambar lama jika ada
            if ($slider->image && file_exists(public_path($destinationPath . $slider->image))) {
                unlink(public_path($destinationPath . $slider->image));
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
            $input['image'] = $slider->image;
        }
    
        // Update data slider
        $slider->update($input);
    
        // Simpan log histori
        $loggedInUserId = Auth::id();
        $this->simpanLogHistori(
            'Update',
            'Slider',
            $slider->id,
            $loggedInUserId,
            json_encode($oldData),
            json_encode($slider->toArray())
        );
    
        return redirect()->route('sliders.index')->with('success', 'Data berhasil diperbarui');
    }








    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Sliders  $slider
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Cari slider berdasarkan ID
        $slider = Slider::findOrFail($id);

      
        try {


            // Hapus file gambar jika ada
            if (!empty($slider->image)) {
                $imagePath = public_path('upload/sliders/' . $slider->image);
                if (file_exists($imagePath)) {
                    @unlink($imagePath); // Menghapus file gambar
                }
            }



            // Hapus slider dari tabel sliders
            $slider->delete();

            // Mendapatkan ID pengguna yang sedang login
            $loggedInUserId = Auth::id();

            // Simpan log histori untuk operasi Delete
            $this->simpanLogHistori('Delete', 'Slider', $id, $loggedInUserId, json_encode($slider), null);

            // Commit slider
            DB::commit();

            // Redirect kembali dengan pesan sukses
            return redirect()->route('sliders.index')->with('success', 'Slider berhasil dihapus');
        } catch (\Exception $e) {
            // Rollback slider jika terjadi error
            DB::rollBack();

            // Kembalikan pesan error
            return redirect()->route('sliders.index')->with('error', 'Gagal menghapus slider: ' . $e->getMessage());
        }
    }
}
