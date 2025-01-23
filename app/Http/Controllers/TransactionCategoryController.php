<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TransactionCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:transactioncategory-list|transactioncategory-create|transactioncategory-edit|transactioncategory-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:transactioncategory-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:transactioncategory-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:transactioncategory-delete', ['only' => ['destroy']]);
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
        $title = "Halaman Kategori Transaksi";
        $subtitle = "Menu Kategori Transaksi";
        $data_transaction_categories = TransactionCategory::all();
        return view('transaction_category.index', compact('data_transaction_categories', 'title', 'subtitle'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Kategori Transaksi";
        $subtitle = "Menu Tambah Kategori Transaksi";
        return view('transaction_category.create', compact('title', 'subtitle'));
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

        $category = TransactionCategory::create($request->all());

        $loggedInUserId = Auth::id();
        $this->simpanLogHistori('Create', 'Kategori Transaksi', $category->id, $loggedInUserId, null, json_encode($category));
        return redirect()->route('transaction_categories.index')
            ->with('success', 'Kategori Transaksi berhasil dibuat.');
    }





    /**
     * Display the specified resource.
     *
     * @param  \App\TransactionCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id): View

    {
        $title = "Halaman Lihat Kategori Transaksi";
        $subtitle = "Menu Lihat Kategori Transaksi";
        $data_transaction_categories = TransactionCategory::find($id);
        return view('transaction_category.show', compact('data_transaction_categories', 'title', 'subtitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TransactionCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Kategori Transaksi";
        $subtitle = "Menu Edit Kategori Transaksi";
        $data_transaction_categories = TransactionCategory::findOrFail($id);  

        return view('transaction_category.edit', compact('data_transaction_categories', 'title', 'subtitle'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TransactionCategory  $category
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
        $category = TransactionCategory::find($id);

        // Jika data tidak ditemukan
        if (!$category) {
            return redirect()->route('transaction_categories.index')
                ->with('error', 'Data Kategori Transaksi tidak ditemukan.');
        }

        // Menyimpan data lama sebelum update
        $oldTransactionCategorysnData = $category->toArray();

        // Melakukan update data
        $category->update($request->all());

        // Mendapatkan ID pengguna yang sedang login
        $loggedInUserId = Auth::id();

        // Mendapatkan data baru setelah update
        $newTransactionCategorysnData = $category->fresh()->toArray();

        // Menyimpan log histori untuk operasi Update
        $this->simpanLogHistori('Update', 'Kategori Transaksi', $category->id, $loggedInUserId, json_encode($oldTransactionCategorysnData), json_encode($newTransactionCategorysnData));

        return redirect()->route('transaction_categories.index')
            ->with('success', 'Kategori Transaksi berhasil diperbaharui');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TransactionCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = TransactionCategory::find($id);
        $category->delete();
        $loggedInTransactionCategoryId = Auth::id();
        // Simpan log histori untuk operasi Delete dengan category_id yang sedang login dan informasi data yang dihapus
        $this->simpanLogHistori('Delete', 'Kategori Transaksi', $id, $loggedInTransactionCategoryId, json_encode($category), null);
        // Redirect kembali dengan pesan sukses
        return redirect()->route('transaction_categories.index')->with('success', 'Kategori Transaksi berhasil dihapus');
    }
}
