<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:unit-list|unit-create|unit-edit|unit-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:unit-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:unit-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:unit-delete', ['only' => ['destroy']]);
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
        $title = "Halaman Satuan";
        $subtitle = "Menu Satuan";
        $data_units = Unit::all();
        return view('unit.index', compact('data_units', 'title', 'subtitle'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Satuan";
        $subtitle = "Menu Tambah Satuan";
        return view('unit.create', compact('title', 'subtitle'));
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
            'name' => 'required|unique:units,name',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.unique' => 'Nama sudah terdaftar.',
        ]);

        $unit = Unit::create($request->all());

        $loggedInUserId = Auth::id();
        // Simpan log histori untuk operasi Create dengan user_id yang sedang login
        $this->simpanLogHistori('Create', 'Satuan', $unit->id, $loggedInUserId, null, json_encode($unit));
        return redirect()->route('units.index')
            ->with('success', 'Satuan berhasil dibuat.');
    }





    /**
     * Display the specified resource.
     *
     * @param  \App\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show($id): View

    {
        $title = "Halaman Lihat Satuan";
        $subtitle = "Menu Lihat Satuan";
        $data_units = Unit::find($id);
        return view('unit.show', compact('data_units', 'title', 'subtitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Satuan";
        $subtitle = "Menu Edit Satuan";
        $data_units = Unit::findOrFail($id); // Data menu item yang sedang diedit

        return view('unit.edit', compact('data_units', 'title', 'subtitle'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
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
        $unit = Unit::find($id);

        // Jika data tidak ditemukan
        if (!$unit) {
            return redirect()->route('units.index')
                ->with('error', 'Data Satuan tidak ditemukan.');
        }

        // Menyimpan data lama sebelum update
        $oldUnitsnData = $unit->toArray();

        // Melakukan update data
        $unit->update($request->all());

        // Mendapatkan ID pengguna yang sedang login
        $loggedInUserId = Auth::id();

        // Mendapatkan data baru setelah update
        $newUnitsnData = $unit->fresh()->toArray();

        // Menyimpan log histori untuk operasi Update
        $this->simpanLogHistori('Update', 'Satuan', $unit->id, $loggedInUserId, json_encode($oldUnitsnData), json_encode($newUnitsnData));

        return redirect()->route('units.index')
            ->with('success', 'Satuan berhasil diperbaharui');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $unit = Unit::find($id);
        $unit->delete();
        $loggedInUnitId = Auth::id();
        // Simpan log histori untuk operasi Delete dengan unit_id yang sedang login dan informasi data yang dihapus
        $this->simpanLogHistori('Delete', 'Satuan', $id, $loggedInUnitId, json_encode($unit), null);
        // Redirect kembali dengan pesan sukses
        return redirect()->route('units.index')->with('success', 'Satuan berhasil dihapus');
    }
}
