<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\TravelRoute;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
 

class TravelRouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:travel_route-list|travel_route-create|travel_route-edit|travel_route-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:travel_route-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:travel_route-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:travel_route-delete', ['only' => ['destroy']]);
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
        $title = "Halaman Rute";
        $subtitle = "Menu Rute";

        // Ambil data untuk dropdown select
        $data_travel_routes = TravelRoute::all();


        // Kirim semua data ke view
        return view('travel_route.index', compact('data_travel_routes', 'title', 'subtitle'));
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Rute";
        $subtitle = "Menu Tambah Rute";


        // Kirim data ke view
        return view('travel_route.create', compact('title', 'subtitle'));
    }



    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|min:0',
            'description' => 'required|string',
            'position' => 'nullable|integer|min:0',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:4096',
        ], [
            'name.required' => 'Nama Rute wajib diisi.',
            'name.string' => 'Nama Rute harus berupa teks.',
            'name.max' => 'Nama Rute tidak boleh lebih dari 255 karakter.',
    
           
            'price.required' => 'Harga wajib diisi.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',
    
            'description.required' => 'Deskripsi wajib diisi.',
            'description.string' => 'Deskripsi harus berupa teks.',
    
            'position.integer' => 'Urutan harus berupa angka.',
            'position.min' => 'Urutan tidak boleh kurang dari 0.',
    
            'image.required' => 'Gambar wajib diunggah.',
            'image.image' => 'Gambar harus berupa file gambar.',
            'image.mimes' => 'Format gambar harus jpeg, jpg, atau png.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 4 MB.',
        ]);
    
        // Mengambil semua input dan memproses harga
        $input = $request->all();
    
        // Menghilangkan karakter koma atau titik pada harga
        $input['price'] = str_replace([',', '.'], '', $input['price']);
    
        if ($image = $request->file('image')) {
            $destinationPath = 'upload/travel_routes/';
    
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
    
        $travel_route = TravelRoute::create($input);
    
        $loggedInUserId = Auth::id();
        $this->simpanLogHistori('Create', 'TravelRoute', $travel_route->id, $loggedInUserId, null, json_encode($travel_route));
    
        return redirect()->route('travel_routes.index')->with('success', 'Data berhasil disimpan');
    }
    










    /**
     * Display the specified resource.
     *
     * @param  \App\TravelRoutes  $travel_route
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // Judul untuk halaman
        $title = "Halaman Lihat Rute";
        $subtitle = "Menu Lihat Rute";



        // Ambil data travel_route berdasarkan ID
        $data_travel_routes = TravelRoute::findOrFail($id);

        // Kembalikan view dengan membawa data produk
        return view('travel_route.show', compact(
            'title',
            'subtitle',

            'data_travel_routes',
        ));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TravelRoutes  $travel_route
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Rute";
        $subtitle = "Menu Edit Rute";


        // Ambil data travel_route berdasarkan ID
        $data_travel_routes = TravelRoute::findOrFail($id);

        // Kirim data ke view
        return view('travel_route.edit', compact(
            'title',
            'subtitle',

            'data_travel_routes',
        ));
    }


    public function update(Request $request, TravelRoute $travel_route): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|min:0',
            'description' => 'required|string',
            'position' => 'nullable|integer|min:0',
            'image' => 'image|mimes:jpeg,jpg,png|max:4096',
        ], [
            'name.required' => 'Nama Rute wajib diisi.',
            'name.string' => 'Nama Rute harus berupa teks.',
            'name.max' => 'Nama Rute tidak boleh lebih dari 255 karakter.',
    
           
    
            'price.required' => 'Harga wajib diisi.',
            'price.min' => 'Harga tidak boleh kurang dari 0.',
    
            'description.required' => 'Deskripsi wajib diisi.',
            'description.string' => 'Deskripsi harus berupa teks.',
    
            'position.integer' => 'Urutan harus berupa angka.',
            'position.min' => 'Urutan tidak boleh kurang dari 0.',
    
            'image.image' => 'Gambar harus berupa file gambar.',
            'image.mimes' => 'Format gambar harus jpeg, jpg, atau png.',
            'image.max' => 'Ukuran gambar tidak boleh lebih dari 4 MB.',
        ]);
    
        // Simpan data lama untuk log
        $oldData = $travel_route->toArray();
    
        $input = $request->all();
    
        // Menghilangkan titik dan koma pada harga
        if (isset($input['price'])) {
            $input['price'] = str_replace([',', '.'], '', $input['price']);
        }
    
        if ($image = $request->file('image')) {
            $destinationPath = 'upload/travel_routes/';
    
            // Hapus gambar lama jika ada
            if ($travel_route->image && file_exists(public_path($destinationPath . $travel_route->image))) {
                unlink(public_path($destinationPath . $travel_route->image));
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
            $input['image'] = $travel_route->image;
        }
    
        // Update data travel_route
        $travel_route->update($input);
    
        // Simpan log histori
        $loggedInUserId = Auth::id();
        $this->simpanLogHistori(
            'Update',
            'TravelRoute',
            $travel_route->id,
            $loggedInUserId,
            json_encode($oldData),
            json_encode($travel_route->toArray())
        );
    
        return redirect()->route('travel_routes.index')->with('success', 'Data berhasil diperbarui');
    }
    








    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TravelRoutes  $travel_route
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Cari travel_route berdasarkan ID
        $travel_route = TravelRoute::findOrFail($id);

      
        try {


            // Hapus file gambar jika ada
            if (!empty($travel_route->image)) {
                $imagePath = public_path('upload/travel_routes/' . $travel_route->image);
                if (file_exists($imagePath)) {
                    @unlink($imagePath); // Menghapus file gambar
                }
            }



            // Hapus travel_route dari tabel travel_routes
            $travel_route->delete();

            // Mendapatkan ID pengguna yang sedang login
            $loggedInUserId = Auth::id();

            // Simpan log histori untuk operasi Delete
            $this->simpanLogHistori('Delete', 'TravelRoute', $id, $loggedInUserId, json_encode($travel_route), null);

            // Commit travel_route
            DB::commit();

            // Redirect kembali dengan pesan sukses
            return redirect()->route('travel_routes.index')->with('success', 'TravelRoute berhasil dihapus');
        } catch (\Exception $e) {
            // Rollback travel_route jika terjadi error
            DB::rollBack();

            // Kembalikan pesan error
            return redirect()->route('travel_routes.index')->with('error', 'Gagal menghapus travel_route: ' . $e->getMessage());
        }
    }
}
