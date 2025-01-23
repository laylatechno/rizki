<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:permission-list|permission-create|permission-edit|permission-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:permission-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:permission-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
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
    public function index(): View
    {
        $title = "Halaman Permission";
        $subtitle = "Menu Permission";
        $data_permission = Permission::orderByRaw("CASE 
            WHEN urutan IS NULL THEN 1 
            WHEN urutan = 0 THEN 2 
            ELSE 0 
        END, urutan ASC")->get();

        return view('permission.index', compact('data_permission', 'title', 'subtitle'));
    }




    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Permission";
        $subtitle = "Menu Tambah Permission";
        return view('permission.create', compact('title', 'subtitle'));
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
            'name' => [
                'required',
                Rule::unique('permissions')->where(function ($query) use ($request) {
                    return $query->where('guard_name', $request->guard_name);
                }),
            ],
            'guard_name' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.unique' => 'Nama sudah terdaftar.',
            'guard_name.required' => 'Guard wajib diisi.',
        ]);

        $permission = Permission::create($request->all());

        $loggedInUserId = Auth::id();
        // Simpan log histori untuk operasi Create dengan user_id yang sedang login
        $this->simpanLogHistori('Create', 'Permission', $permission->id, $loggedInUserId, null, json_encode($permission));
        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil dibuat.');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show($id): View

    {
        $title = "Halaman Lihat Permission";
        $subtitle = "Menu Lihat Permission";
        $data_permission = Permission::find($id);
        return view('permission.show', compact('data_permission', 'title', 'subtitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Permission";
        $subtitle = "Menu Edit Permission";
        $data_permission = Permission::find($id);
        return view('permission.edit', compact('data_permission', 'title', 'subtitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'guard_name' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'guard_name.required' => 'Guard wajib diisi.',
        ]);

        // Menyimpan data lama sebelum update
        $oldPermissionData = $permission->toArray(); // Menyimpan semua field termasuk created_at dan updated_at

        // Melakukan update permission
        $permission->update($request->all());

        // Mendapatkan ID pengguna yang sedang login
        $loggedInUserId = Auth::id();

        // Mendapatkan data baru setelah update
        $newPermissionData = $permission->fresh()->toArray(); // Mengambil data terbaru setelah update

        // Menyimpan log histori untuk operasi Update
        $this->simpanLogHistori('Update', 'Permission', $permission->id, $loggedInUserId, json_encode($oldPermissionData), json_encode($newPermissionData));

        return redirect()->route('permissions.index')
            ->with('success', 'Permission berhasil diperbaharui');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);
        $permission->delete();
        $loggedInGaleriId = Auth::id();
        // Simpan log histori untuk operasi Delete dengan permission_id yang sedang login dan informasi data yang dihapus
        $this->simpanLogHistori('Delete', 'Permission', $id, $loggedInGaleriId, json_encode($permission), null);
        // Redirect kembali dengan pesan sukses
        return redirect()->route('permissions.index')->with('success', 'Permission berhasil dihapus');
    }
}
