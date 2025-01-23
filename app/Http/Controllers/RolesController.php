<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LogHistori;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
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
        $title = "Halaman Role";
        $subtitle = "Menu Role";
        $data_role = Role::orderBy('id', 'DESC')->paginate(5);
        return view('role.index', compact('data_role', 'title', 'subtitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Role";
        $subtitle = "Menu Tambah Role";
        $permission = Permission::get();
        return view('role.create', compact('permission', 'title', 'subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        // Validasi input
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.unique' => 'Nama sudah terdaftar.',
            'permission.required' => 'Permission wajib diisi.',
        ]);
    
        // Mengonversi array permission menjadi ID
        $permissionsID = array_map(
            function ($value) {
                return (int) $value;
            },
            $request->input('permission')
        );
    
        // Membuat role baru
        $role = Role::create(['name' => $request->input('name')]);
    
        // Menyinkronkan permissions dengan role yang baru dibuat
        $role->syncPermissions($permissionsID);
    
        // Mendapatkan ID pengguna yang sedang login
        $loggedInUserId = Auth::id();
    
        // Mendapatkan nama permission yang disinkronkan
        $permissions = $role->permissions->pluck('name')->toArray();
    
        // Menyusun data log
        $logData = [
            'name' => $role->name,
            'permissions' => $permissions
        ];
    
        // Simpan log histori untuk operasi Create dengan user_id yang sedang login
        $this->simpanLogHistori('Create', 'Role', $role->id, $loggedInUserId, null, json_encode($logData));
    
        // Redirect kembali dengan pesan sukses
        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil dibuat');
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $title = "Halaman Lihat Role";
        $subtitle = "Menu Lihat Role";
        $data_role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('role.show', compact('data_role', 'rolePermissions', 'title', 'subtitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Role";
        $subtitle = "Menu Edit Role";
        $data_role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = $data_role->permissions->pluck('id')->toArray(); // Menggunakan relasi permissions()


        return view('role.edit', compact('data_role', 'permission', 'rolePermissions', 'title', 'subtitle'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // Validasi input
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'permission.required' => 'Permission wajib diisi.',
        ]);
    
        // Cari role berdasarkan ID
        $role = Role::find($id);
    
        // Pastikan role ditemukan
        if (!$role) {
            return redirect()->route('roles.index')
                ->with('error', 'Role tidak ditemukan');
        }
    
        // Menyimpan data lama untuk log
        $oldPermissions = $role->permissions->pluck('name')->toArray();
        $oldRoleData = $role->toArray();
    
        // Update nama role
        $role->name = $request->input('name');
        $role->save();
    
        // Proses permissions yang dikirim
        $permissionsID = array_map(
            function ($value) {
                return (int)$value;
            },
            $request->input('permission')
        );
    
        // Sinkronisasi permissions dengan role
        $role->syncPermissions($permissionsID);
    
        // Mendapatkan data permissions yang baru
        $newPermissions = $role->permissions->pluck('name')->toArray();
        $loggedInUserId = Auth::id();
    
        // Membuat data untuk log histori
        $oldData = [
            'name' => $role->name,
            'permissions' => $oldPermissions,  // Permissions lama
        ];
    
        $newData = [
            'name' => $role->name,
            'permissions' => $newPermissions,  // Permissions baru
        ];
    
        // Simpan log histori untuk operasi Update
        $this->simpanLogHistori('Update', 'Role', $role->id, $loggedInUserId, json_encode($oldData), json_encode($newData));
    
        // Redirect kembali dengan pesan sukses
        return redirect()->route('roles.index')
            ->with('success', 'Role berhasil diperbaharui');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function destroy($id): RedirectResponse
{
    // Cari role berdasarkan ID, langsung lempar error jika tidak ditemukan
    $role = Role::findOrFail($id);

    // Ambil permissions yang terkait dengan role
    $permissions = $role->permissions->pluck('name')->toArray();

    // Gabungkan data role dengan permissions dalam array
    $roleData = [
        'name' => $role->name,
        'permissions' => $permissions
    ];

    // Simpan log histori sebelum menghapus role, termasuk data permissions
    $this->simpanLogHistori('Delete', 'Role', $role->id, Auth::id(), json_encode($roleData), null);

    // Hapus role
    $role->delete();

    // Redirect kembali dengan pesan sukses
    return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus');
}


 
     
     
}
