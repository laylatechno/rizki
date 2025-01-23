<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ProfilController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:profil-list|profil-create|profil-edit|profil-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:profil-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:profil-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:profil-delete', ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $title = "Halaman Profil";
        $subtitle = "Menu Profil";
        $data_profil = Profil::all();

        return view('profil.index', compact('data_profil', 'title', 'subtitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('profil.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        request()->validate([
            'name' => 'required',
            'detail' => 'required',
        ]);

        Profil::create($request->all());

        return redirect()->route('profil.index')
            ->with('success', 'Profil created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Profil  $profil
     * @return \Illuminate\Http\Response
     */
    public function show(Profil $profil): View
    {
        return view('profil.show', compact('profil'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Profil  $profil
     * @return \Illuminate\Http\Response
     */
    public function edit(): View
    {
        // Mengambil profil dengan ID 1
        $profil = Profil::findOrFail(1);
        $title = "Halaman Profil";
        $subtitle = "Menu Profil";
        return view('profil.edit', compact('profil', 'title', 'subtitle'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Profil  $profil
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $this->validate($request, [
            'nama_profil' => 'required',
            'no_telp' => 'required|numeric',
            'no_wa' => 'required|numeric',
            'email' => 'required|email',
            'logo' => 'image|mimes:jpeg,png,jpg,gif|max:6048',
            'logo_dark' => 'image|mimes:jpeg,png,jpg,gif|max:6048',
            'favicon' => 'image|mimes:jpeg,png,jpg,gif|max:6048',
            'banner' => 'image|mimes:jpeg,png,jpg,gif|max:6048',
        ], [
            'nama_profil.required' => 'Nama profil wajib diisi.',
            'no_telp.required' => 'No Telp wajib diisi.',
            'no_telp.numeric' => 'No Telp harus berupa angka.',
            'no_wa.required' => 'No WA wajib diisi.',
            'no_wa.numeric' => 'No WA harus berupa angka.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'nama_profil.unique' => 'Nama profil sudah ada.',
            'logo.image' => 'Gambar harus dalam format jpeg, jpg, atau png',
            'logo.mimes' => 'Format logo harus jpeg, jpg, atau png',
            'logo.max' => 'Ukuran logo tidak boleh lebih dari 6 MB',
            'favicon.image' => 'Favicon harus dalam format jpeg, jpg, atau png',
            'favicon.mimes' => 'Format favicon harus jpeg, jpg, atau png',
            'favicon.max' => 'Ukuran favicon tidak boleh lebih dari 6 MB',
            'banner.image' => 'Favicon harus dalam format jpeg, jpg, atau png',
            'banner.mimes' => 'Format banner harus jpeg, jpg, atau png',
            'banner.max' => 'Ukuran banner tidak boleh lebih dari 6 MB',
            'logo_dark.image' => 'Favicon harus dalam format jpeg, jpg, atau png',
            'logo_dark.mimes' => 'Format logo dark harus jpeg, jpg, atau png',
            'logo_dark.max' => 'Ukuran logo dark tidak boleh lebih dari 6 MB',
        ]);

        $profil = Profil::find($id);
        $oldData = $profil->toArray();

        // Proses data input
        $input = $request->all();


        // Cek apakah logo diupload
        if ($request->hasFile('logo')) {
            // Hapus logo sebelumnya jika ada
            $oldPictureFileName = $profil->logo;
            if ($oldPictureFileName) {
                $oldFilePath = public_path('upload/profil/' . $oldPictureFileName);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $image = $request->file('logo');
            $destinationPath = 'upload/profil/';

            // Mengambil nama file asli dan ekstensinya
            $originalFileName = $image->getClientOriginalName();

            // Membaca tipe MIME dari file logo
            $imageMimeType = $image->getMimeType();

            // Menyaring hanya tipe MIME logo yang didukung (misalnya, image/jpeg, image/png, dll.)
            if (strpos($imageMimeType, 'image/') === 0) {
                // Menggabungkan waktu dengan nama file asli
                $imageName = date('YmdHis') . '_' . str_replace(' ', '_', $originalFileName);

                // Simpan logo asli ke tujuan yang diinginkan
                $image->move($destinationPath, $imageName);

                // Path logo asli
                $sourceImagePath = public_path($destinationPath . $imageName);

                // Path untuk menyimpan logo WebP
                $webpImagePath = $destinationPath . pathinfo($imageName, PATHINFO_FILENAME) . '.webp';

                // Membaca logo asli dan mengonversinya ke WebP jika tipe MIME-nya didukung
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

                // Jika logo asli berhasil dibaca
                if ($sourceImage !== false) {
                    // Membuat logo baru dalam format WebP
                    imagewebp($sourceImage, $webpImagePath);

                    // Hapus logo asli dari memori
                    imagedestroy($sourceImage);

                    // Hapus file asli setelah konversi selesai
                    @unlink($sourceImagePath);

                    // Simpan hanya nama file logo ke dalam atribut profil
                    $input['logo'] = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
                } else {
                    // Gagal membaca logo asli, tangani kasus ini sesuai kebutuhan Anda
                }
            } else {
                // Tipe MIME logo tidak didukung, tangani kasus ini sesuai kebutuhan Anda
            }
        }


        if ($request->hasFile('logo_dark')) {
            // Hapus logo_dark sebelumnya jika ada
            $oldPictureFileName = $profil->logo_dark;
            if ($oldPictureFileName) {
                $oldFilePath = public_path('upload/profil/' . $oldPictureFileName);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $image = $request->file('logo_dark');
            $destinationPath = 'upload/profil/';

            // Mengambil nama file asli dan ekstensinya
            $originalFileName = $image->getClientOriginalName();

            // Membaca tipe MIME dari file logo_dark
            $imageMimeType = $image->getMimeType();

            // Menyaring hanya tipe MIME logo_dark yang didukung (misalnya, image/jpeg, image/png, dll.)
            if (strpos($imageMimeType, 'image/') === 0) {
                // Menggabungkan waktu dengan nama file asli
                $imageName = date('YmdHis') . '_' . str_replace(' ', '_', $originalFileName);

                // Simpan logo_dark asli ke tujuan yang diinginkan
                $image->move($destinationPath, $imageName);

                // Path logo_dark asli
                $sourceImagePath = public_path($destinationPath . $imageName);

                // Path untuk menyimpan logo_dark WebP
                $webpImagePath = $destinationPath . pathinfo($imageName, PATHINFO_FILENAME) . '.webp';

                // Membaca logo_dark asli dan mengonversinya ke WebP jika tipe MIME-nya didukung
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

                // Jika logo_dark asli berhasil dibaca
                if ($sourceImage !== false) {
                    // Membuat logo_dark baru dalam format WebP
                    imagewebp($sourceImage, $webpImagePath);

                    // Hapus logo_dark asli dari memori
                    imagedestroy($sourceImage);

                    // Hapus file asli setelah konversi selesai
                    @unlink($sourceImagePath);

                    // Simpan hanya nama file logo_dark ke dalam atribut profil
                    $input['logo_dark'] = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
                } else {
                    // Gagal membaca logo_dark asli, tangani kasus ini sesuai kebutuhan Anda
                }
            } else {
                // Tipe MIME logo_dark tidak didukung, tangani kasus ini sesuai kebutuhan Anda
            }
        }


        if ($request->hasFile('favicon')) {
            // Hapus favicon sebelumnya jika ada
            $oldPictureFileName = $profil->favicon;
            if ($oldPictureFileName) {
                $oldFilePath = public_path('upload/profil/' . $oldPictureFileName);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $image = $request->file('favicon');
            $destinationPath = 'upload/profil/';

            // Mengambil nama file asli dan ekstensinya
            $originalFileName = $image->getClientOriginalName();

            // Membaca tipe MIME dari file favicon
            $imageMimeType = $image->getMimeType();

            // Menyaring hanya tipe MIME favicon yang didukung (misalnya, image/jpeg, image/png, dll.)
            if (strpos($imageMimeType, 'image/') === 0) {
                // Menggabungkan waktu dengan nama file asli
                $imageName = date('YmdHis') . '_' . str_replace(' ', '_', $originalFileName);

                // Simpan favicon asli ke tujuan yang diinginkan
                $image->move($destinationPath, $imageName);

                // Path favicon asli
                $sourceImagePath = public_path($destinationPath . $imageName);

                // Path untuk menyimpan favicon WebP
                $webpImagePath = $destinationPath . pathinfo($imageName, PATHINFO_FILENAME) . '.webp';

                // Membaca favicon asli dan mengonversinya ke WebP jika tipe MIME-nya didukung
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

                // Jika favicon asli berhasil dibaca
                if ($sourceImage !== false) {
                    // Membuat favicon baru dalam format WebP
                    imagewebp($sourceImage, $webpImagePath);

                    // Hapus favicon asli dari memori
                    imagedestroy($sourceImage);

                    // Hapus file asli setelah konversi selesai
                    @unlink($sourceImagePath);

                    // Simpan hanya nama file favicon ke dalam atribut profil
                    $input['favicon'] = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
                } else {
                    // Gagal membaca favicon asli, tangani kasus ini sesuai kebutuhan Anda
                }
            } else {
                // Tipe MIME favicon tidak didukung, tangani kasus ini sesuai kebutuhan Anda
            }
        }


        if ($request->hasFile('banner')) {
            // Hapus banner sebelumnya jika ada
            $oldPictureFileName = $profil->banner;
            if ($oldPictureFileName) {
                $oldFilePath = public_path('upload/profil/' . $oldPictureFileName);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $image = $request->file('banner');
            $destinationPath = 'upload/profil/';

            // Mengambil nama file asli dan ekstensinya
            $originalFileName = $image->getClientOriginalName();

            // Membaca tipe MIME dari file banner
            $imageMimeType = $image->getMimeType();

            // Menyaring hanya tipe MIME banner yang didukung (misalnya, image/jpeg, image/png, dll.)
            if (strpos($imageMimeType, 'image/') === 0) {
                // Menggabungkan waktu dengan nama file asli
                $imageName = date('YmdHis') . '_' . str_replace(' ', '_', $originalFileName);

                // Simpan banner asli ke tujuan yang diinginkan
                $image->move($destinationPath, $imageName);

                // Path banner asli
                $sourceImagePath = public_path($destinationPath . $imageName);

                // Path untuk menyimpan banner WebP
                $webpImagePath = $destinationPath . pathinfo($imageName, PATHINFO_FILENAME) . '.webp';

                // Membaca banner asli dan mengonversinya ke WebP jika tipe MIME-nya didukung
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

                // Jika banner asli berhasil dibaca
                if ($sourceImage !== false) {
                    // Membuat banner baru dalam format WebP
                    imagewebp($sourceImage, $webpImagePath);

                    // Hapus banner asli dari memori
                    imagedestroy($sourceImage);

                    // Hapus file asli setelah konversi selesai
                    @unlink($sourceImagePath);

                    // Simpan hanya nama file banner ke dalam atribut profil
                    $input['banner'] = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
                } else {
                    // Gagal membaca banner asli, tangani kasus ini sesuai kebutuhan Anda
                }
            } else {
                // Tipe MIME banner tidak didukung, tangani kasus ini sesuai kebutuhan Anda
            }
        }

        if ($request->hasFile('bg_login')) {
            // Hapus bg_login sebelumnya jika ada
            $oldPictureFileName = $profil->bg_login;
            if ($oldPictureFileName) {
                $oldFilePath = public_path('upload/profil/' . $oldPictureFileName);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $image = $request->file('bg_login');
            $destinationPath = 'upload/profil/';

            // Mengambil nama file asli dan ekstensinya
            $originalFileName = $image->getClientOriginalName();

            // Membaca tipe MIME dari file bg_login
            $imageMimeType = $image->getMimeType();

            // Menyaring hanya tipe MIME bg_login yang didukung (misalnya, image/jpeg, image/png, dll.)
            if (strpos($imageMimeType, 'image/') === 0) {
                // Menggabungkan waktu dengan nama file asli
                $imageName = date('YmdHis') . '_' . str_replace(' ', '_', $originalFileName);

                // Simpan bg_login asli ke tujuan yang diinginkan
                $image->move($destinationPath, $imageName);

                // Path bg_login asli
                $sourceImagePath = public_path($destinationPath . $imageName);

                // Path untuk menyimpan bg_login WebP
                $webpImagePath = $destinationPath . pathinfo($imageName, PATHINFO_FILENAME) . '.webp';

                // Membaca bg_login asli dan mengonversinya ke WebP jika tipe MIME-nya didukung
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

                // Jika bg_login asli berhasil dibaca
                if ($sourceImage !== false) {
                    // Membuat bg_login baru dalam format WebP
                    imagewebp($sourceImage, $webpImagePath);

                    // Hapus bg_login asli dari memori
                    imagedestroy($sourceImage);

                    // Hapus file asli setelah konversi selesai
                    @unlink($sourceImagePath);

                    // Simpan hanya nama file bg_login ke dalam atribut profil
                    $input['bg_login'] = pathinfo($imageName, PATHINFO_FILENAME) . '.webp';
                } else {
                    // Gagal membaca bg_login asli, tangani kasus ini sesuai kebutuhan Anda
                }
            } else {
                // Tipe MIME bg_login tidak didukung, tangani kasus ini sesuai kebutuhan Anda
            }
        }


        // Update data profil
        $profil->update($input);

        // Simpan log histori
        $this->simpanLogHistori('Update', 'Profil', $profil->id, Auth::id(), json_encode($oldData), json_encode($input));

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }

    public function update_setting(Request $request, $id)
    {
        try {
            // $request->validate([

            // ], [

            // ]);

            // Ambil data profil yang akan diupdate
            $profil = Profil::findOrFail($id);

            // Setel data yang akan diupdate
            $input = $request->all();



            // Membuat profil baru dan mendapatkan data pengguna yang baru dibuat
            $profil = Profil::findOrFail($id);

            // Mendapatkan ID pengguna yang sedang login
            $loggedInProfilId = Auth::id();

            // Simpan log histori untuk operasi Update dengan profil_id yang sedang login
            $this->simpanLogHistori('Update', 'Profil', $profil->id, $loggedInProfilId, json_encode($profil), json_encode($input));

            $profil->update($input);
            // Set flash message untuk notifikasi sukses
            return redirect()->back()
                ->with('success', 'Data berhasil diupdate');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->errors())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Profil  $profil
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profil $profil): RedirectResponse
    {
        $profil->delete();

        return redirect()->route('profil.index')
            ->with('success', 'Profil deleted successfully');
    }

    private function simpanLogHistori($aksi, $tabelAsal, $idEntitas, $pengguna, $dataLama, $dataBaru)
    {
        $log = new LogHistori();
        $log->tabel_asal = $tabelAsal;
        $log->id_entitas = $idEntitas;
        $log->aksi = $aksi;
        $log->waktu = now(); // Menggunakan waktu saat ini
        $log->pengguna = $pengguna;
        $log->data_lama = $dataLama;
        $log->data_baru = $dataBaru;
        $log->save();
    }
}
