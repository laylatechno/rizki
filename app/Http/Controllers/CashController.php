<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\Cash;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:cash-list|cash-create|cash-edit|cash-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:cash-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:cash-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:cash-delete', ['only' => ['destroy']]);
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
        $title = "Halaman Cash";
        $subtitle = "Menu Cash";
        $data_cashs = Cash::all();
        return view('cash.index', compact('data_cashs', 'title', 'subtitle'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Cash";
        $subtitle = "Menu Tambah Cash";
        return view('cash.create', compact('title', 'subtitle'));
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
            'name' => 'required|unique:cash,name',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.unique' => 'Nama sudah terdaftar.',
        ]);

        // Menghapus separator ',' dan '.' pada amount
        $amount = str_replace([',', '.'], '', $request->input('amount'));

        // Membuat data baru
        $cash = Cash::create(array_merge(
            $request->except('amount'),
            ['amount' => $amount]
        ));

        $loggedInUserId = Auth::id();
        // Simpan log histori untuk operasi Create dengan user_id yang sedang login
        $this->simpanLogHistori('Create', 'Cash', $cash->id, $loggedInUserId, null, json_encode($cash));

        return redirect()->route('cash.index')
            ->with('success', 'Cash berhasil dibuat.');
    }





    /**
     * Display the specified resource.
     *
     * @param  \App\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function show($id): View

    {
        $title = "Halaman Lihat Cash";
        $subtitle = "Menu Lihat Cash";
        $data_cashs = Cash::find($id);
        return view('cash.show', compact('data_cashs', 'title', 'subtitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Cash";
        $subtitle = "Menu Edit Cash";
        $data_cashs = Cash::findOrFail($id); // Data menu item yang sedang diedit

        return view('cash.edit', compact('data_cashs', 'title', 'subtitle'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Cash  $cash
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
        $cash = Cash::find($id);
    
        // Jika data tidak ditemukan
        if (!$cash) {
            return redirect()->route('cash.index')
                ->with('error', 'Data Cash tidak ditemukan.');
        }
    
        // Menyimpan data lama sebelum update
        $oldCashsnData = $cash->toArray();
    
        // Menghapus separator ',' dan '.' pada amount
        $amount = str_replace([',', '.'], '', $request->input('amount'));
    
        // Melakukan update data
        $cash->update(array_merge(
            $request->except('amount'),
            ['amount' => $amount]
        ));
    
        // Mendapatkan ID pengguna yang sedang login
        $loggedInUserId = Auth::id();
    
        // Mendapatkan data baru setelah update
        $newCashsnData = $cash->fresh()->toArray();
    
        // Menyimpan log histori untuk operasi Update
        $this->simpanLogHistori('Update', 'Cash', $cash->id, $loggedInUserId, json_encode($oldCashsnData), json_encode($newCashsnData));
    
        return redirect()->route('cash.index')
            ->with('success', 'Cash berhasil diperbaharui');
    }
    



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cash  $cash
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cash = Cash::find($id);
        $cash->delete();
        $loggedInCashId = Auth::id();
        // Simpan log histori untuk operasi Delete dengan cash_id yang sedang login dan informasi data yang dihapus
        $this->simpanLogHistori('Delete', 'Cash', $id, $loggedInCashId, json_encode($cash), null);
        // Redirect kembali dengan pesan sukses
        return redirect()->route('cash.index')->with('success', 'Cash berhasil dihapus');
    }
}
