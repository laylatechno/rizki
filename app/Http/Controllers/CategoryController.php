<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:category-list|category-create|category-edit|category-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:category-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:category-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:category-delete', ['only' => ['destroy']]);
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
        $title = "Halaman Kategori Produk";
        $subtitle = "Menu Kategori Produk";
        $data_categories = Category::all();
        return view('category.index', compact('data_categories', 'title', 'subtitle'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Kategori Produk";
        $subtitle = "Menu Tambah Kategori Produk";
        return view('category.create', compact('title', 'subtitle'));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:categories,name',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.unique' => 'Nama sudah terdaftar.',
        ]);

        $category = Category::create($request->all());

        $loggedInUserId = Auth::id();
        $this->simpanLogHistori('Create', 'Kategori Produk', $category->id, $loggedInUserId, null, json_encode($category));
        return redirect()->route('categories.index')
            ->with('success', 'Kategori Produk berhasil dibuat.');
    }





    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id): View

    {
        $title = "Halaman Lihat Kategori Produk";
        $subtitle = "Menu Lihat Kategori Produk";
        $data_categories = Category::find($id);
        return view('category.show', compact('data_categories', 'title', 'subtitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Kategori Produk";
        $subtitle = "Menu Edit Kategori Produk";
        $data_categories = Category::findOrFail($id);  

        return view('category.edit', compact('data_categories', 'title', 'subtitle'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // Validasi input
        $this->validate($request, [
            'name' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi.',
        ]);

        // Cari data berdasarkan ID
        $category = Category::find($id);

        // Jika data tidak ditemukan
        if (!$category) {
            return redirect()->route('categories.index')
                ->with('error', 'Data Kategori Produk tidak ditemukan.');
        }

        // Menyimpan data lama sebelum update
        $oldCategorysnData = $category->toArray();

        // Melakukan update data
        $category->update($request->all());

        // Mendapatkan ID pengguna yang sedang login
        $loggedInUserId = Auth::id();

        // Mendapatkan data baru setelah update
        $newCategorysnData = $category->fresh()->toArray();

        // Menyimpan log histori untuk operasi Update
        $this->simpanLogHistori('Update', 'Kategori Produk', $category->id, $loggedInUserId, json_encode($oldCategorysnData), json_encode($newCategorysnData));

        return redirect()->route('categories.index')
            ->with('success', 'Kategori Produk berhasil diperbaharui');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        $loggedInCategoryId = Auth::id();
        // Simpan log histori untuk operasi Delete dengan category_id yang sedang login dan informasi data yang dihapus
        $this->simpanLogHistori('Delete', 'Kategori Produk', $id, $loggedInCategoryId, json_encode($category), null);
        // Redirect kembali dengan pesan sukses
        return redirect()->route('categories.index')->with('success', 'Kategori Produk berhasil dihapus');
    }
}
