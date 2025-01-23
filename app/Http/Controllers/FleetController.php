<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\Fleet;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
 

class FleetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:fleet-list|fleet-create|fleet-edit|fleet-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:fleet-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:fleet-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:fleet-delete', ['only' => ['destroy']]);
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
        $title = "Halaman Armada";
        $subtitle = "Menu Armada";

        // Ambil data untuk dropdown select
        $data_fleets = Fleet::all();


        // Kirim semua data ke view
        return view('fleet.index', compact('data_fleets', 'title', 'subtitle'));
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Armada";
        $subtitle = "Menu Tambah Armada";


        // Kirim data ke view
        return view('fleet.create', compact('title', 'subtitle'));
    }



    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'price' => 'required|min:0',
            'description' => 'required|string',
            'position' => 'nullable|integer|min:0',
            'image' => 'required|image|mimes:jpeg,jpg,png|max:4096',
        ], [
            'name.required' => 'Nama Armada wajib diisi.',
            'name.string' => 'Nama Armada harus berupa teks.',
            'name.max' => 'Nama Armada tidak boleh lebih dari 255 karakter.',
    
            'category.string' => 'Kategori harus berupa teks.',
            'category.max' => 'Kategori tidak boleh lebih dari 255 karakter.',
    
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
            $destinationPath = 'upload/fleets/';
    
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
    
        $fleet = Fleet::create($input);
    
        $loggedInUserId = Auth::id();
        $this->simpanLogHistori('Create', 'Fleet', $fleet->id, $loggedInUserId, null, json_encode($fleet));
    
        return redirect()->route('fleets.index')->with('success', 'Data berhasil disimpan');
    }
    










    /**
     * Display the specified resource.
     *
     * @param  \App\Fleets  $fleet
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // Judul untuk halaman
        $title = "Halaman Lihat Fleet";
        $subtitle = "Menu Lihat Fleet";



        // Ambil data fleet berdasarkan ID
        $data_fleets = Fleet::findOrFail($id);

        // Kembalikan view dengan membawa data produk
        return view('fleet.show', compact(
            'title',
            'subtitle',

            'data_fleets',
        ));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Fleets  $fleet
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Fleet";
        $subtitle = "Menu Edit Fleet";


        // Ambil data fleet berdasarkan ID
        $data_fleets = Fleet::findOrFail($id);

        // Kirim data ke view
        return view('fleet.edit', compact(
            'title',
            'subtitle',

            'data_fleets',
        ));
    }


    public function update(Request $request, Fleet $fleet): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'price' => 'required|min:0',
            'description' => 'required|string',
            'position' => 'nullable|integer|min:0',
            'image' => 'image|mimes:jpeg,jpg,png|max:4096',
        ], [
            'name.required' => 'Nama Armada wajib diisi.',
            'name.string' => 'Nama Armada harus berupa teks.',
            'name.max' => 'Nama Armada tidak boleh lebih dari 255 karakter.',
    
            'category.string' => 'Kategori harus berupa teks.',
            'category.max' => 'Kategori tidak boleh lebih dari 255 karakter.',
    
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
        $oldData = $fleet->toArray();
    
        $input = $request->all();
    
        // Menghilangkan titik dan koma pada harga
        if (isset($input['price'])) {
            $input['price'] = str_replace([',', '.'], '', $input['price']);
        }
    
        if ($image = $request->file('image')) {
            $destinationPath = 'upload/fleets/';
    
            // Hapus gambar lama jika ada
            if ($fleet->image && file_exists(public_path($destinationPath . $fleet->image))) {
                unlink(public_path($destinationPath . $fleet->image));
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
            $input['image'] = $fleet->image;
        }
    
        // Update data fleet
        $fleet->update($input);
    
        // Simpan log histori
        $loggedInUserId = Auth::id();
        $this->simpanLogHistori(
            'Update',
            'Fleet',
            $fleet->id,
            $loggedInUserId,
            json_encode($oldData),
            json_encode($fleet->toArray())
        );
    
        return redirect()->route('fleets.index')->with('success', 'Data berhasil diperbarui');
    }
    








    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Fleets  $fleet
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Cari fleet berdasarkan ID
        $fleet = Fleet::findOrFail($id);

      
        try {


            // Hapus file gambar jika ada
            if (!empty($fleet->image)) {
                $imagePath = public_path('upload/fleets/' . $fleet->image);
                if (file_exists($imagePath)) {
                    @unlink($imagePath); // Menghapus file gambar
                }
            }



            // Hapus fleet dari tabel fleets
            $fleet->delete();

            // Mendapatkan ID pengguna yang sedang login
            $loggedInUserId = Auth::id();

            // Simpan log histori untuk operasi Delete
            $this->simpanLogHistori('Delete', 'Fleet', $id, $loggedInUserId, json_encode($fleet), null);

            // Commit fleet
            DB::commit();

            // Redirect kembali dengan pesan sukses
            return redirect()->route('fleets.index')->with('success', 'Fleet berhasil dihapus');
        } catch (\Exception $e) {
            // Rollback fleet jika terjadi error
            DB::rollBack();

            // Kembalikan pesan error
            return redirect()->route('fleets.index')->with('error', 'Gagal menghapus fleet: ' . $e->getMessage());
        }
    }
}
