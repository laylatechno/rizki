<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:supplier-list|supplier-create|supplier-edit|supplier-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:supplier-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:supplier-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:supplier-delete', ['only' => ['destroy']]);
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
        $title = "Halaman Supplier";
        $subtitle = "Menu Supplier";
        $data_suppliers = Supplier::all();
        return view('supplier.index', compact('data_suppliers', 'title', 'subtitle'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Supplier";
        $subtitle = "Menu Tambah Supplier";
        return view('supplier.create', compact('title', 'subtitle'));
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
            'name' => 'required|unique:suppliers,name',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.unique' => 'Nama sudah terdaftar.',
        ]);

        $supplier = Supplier::create($request->all());

        $loggedInUserId = Auth::id();
        // Simpan log histori untuk operasi Create dengan user_id yang sedang login
        $this->simpanLogHistori('Create', 'Supplier', $supplier->id, $loggedInUserId, null, json_encode($supplier));
        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil dibuat.');
    }





    /**
     * Display the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show($id): View

    {
        $title = "Halaman Lihat Supplier";
        $subtitle = "Menu Lihat Supplier";
        $data_suppliers = Supplier::find($id);
        return view('supplier.show', compact('data_suppliers', 'title', 'subtitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Supplier";
        $subtitle = "Menu Edit Supplier";
        $data_suppliers = Supplier::findOrFail($id); // Data menu item yang sedang diedit

        return view('supplier.edit', compact('data_suppliers', 'title', 'subtitle'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Supplier  $supplier
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
        $supplier = Supplier::find($id);

        // Jika data tidak ditemukan
        if (!$supplier) {
            return redirect()->route('suppliers.index')
                ->with('error', 'Data Supplier tidak ditemukan.');
        }

        // Menyimpan data lama sebelum update
        $oldSuppliersnData = $supplier->toArray();

        // Melakukan update data
        $supplier->update($request->all());

        // Mendapatkan ID pengguna yang sedang login
        $loggedInUserId = Auth::id();

        // Mendapatkan data baru setelah update
        $newSuppliersnData = $supplier->fresh()->toArray();

        // Menyimpan log histori untuk operasi Update
        $this->simpanLogHistori('Update', 'Supplier', $supplier->id, $loggedInUserId, json_encode($oldSuppliersnData), json_encode($newSuppliersnData));

        return redirect()->route('suppliers.index')
            ->with('success', 'Supplier berhasil diperbaharui');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();
        $loggedInSupplierId = Auth::id();
        // Simpan log histori untuk operasi Delete dengan supplier_id yang sedang login dan informasi data yang dihapus
        $this->simpanLogHistori('Delete', 'Supplier', $id, $loggedInSupplierId, json_encode($supplier), null);
        // Redirect kembali dengan pesan sukses
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil dihapus');
    }
}
