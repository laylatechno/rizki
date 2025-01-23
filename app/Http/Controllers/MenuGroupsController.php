<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\MenuGroup;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
 

class MenuGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:menugroup-list|menugroup-create|menugroup-edit|menugroup-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:menugroup-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:menugroup-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:menugroup-delete', ['only' => ['destroy']]);
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

    public function updatePositions(Request $request)
    {
        $positions = $request->input('positions'); // Array ID menu group yang terurut
        foreach ($positions as $index => $id) {
            $menu_group = MenuGroup::find($id);
            if ($menu_group) {
                $menu_group->position = $index + 1; // Update posisi berdasarkan index
                $menu_group->save(); // Simpan perubahan
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): View
    {
        $title = "Halaman Menu Group";
        $subtitle = "Menu Menu Group";
        
        $query = MenuGroup::orderBy('position', 'asc');
        
        // Apply search filter if a search term is provided
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $data_menu_group = $query->paginate(20); // Menambahkan pagination dengan 10 item per halaman
    
        return view('menu_group.index', compact('data_menu_group', 'title', 'subtitle'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Menu Group";
        $subtitle = "Menu Tambah Menu Group";
        $data_permission =  Permission::pluck('name', 'name')->all();
        return view('menu_group.create', compact('title', 'subtitle', 'data_permission'));
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
            'name' => 'required|unique:menu_groups,name',
            'permission_name' => 'required',
            'status' => 'required',
            'position' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.unique' => 'Nama sudah terdaftar.',
            'permission_name.required' => 'Nama Permission wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'position.required' => 'Urutan wajib diisi.',
        ]);
    
        // Menyimpan data baru ke database
        $menuGroup = MenuGroup::create($request->all());
    
        // Mendapatkan ID pengguna yang sedang login
        $loggedInUserId = Auth::id();
    
        // Menyimpan log histori
        $newMenuGroupData = $menuGroup->toArray(); // Data baru yang disimpan
        $this->simpanLogHistori('Create', 'MenuGroup', $menuGroup->id, $loggedInUserId, null, json_encode($newMenuGroupData));
    
        return redirect()->route('menu_groups.index')
            ->with('success', 'Menu Group berhasil dibuat.');
    }
    


    /**
     * Display the specified resource.
     *
     * @param  \App\MenuGroup  $menu_group
     * @return \Illuminate\Http\Response
     */
    public function show($id): View

    {
        $title = "Halaman Lihat Menu Group";
        $subtitle = "Menu Lihat Menu Group";
        $data_menu_group = MenuGroup::find($id);
        $data_permission =  Permission::pluck('name', 'name')->all();
        return view('menu_group.show', compact('data_menu_group', 'title', 'subtitle', 'data_permission'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MenuGroup  $menu_group
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Menu Group";
        $subtitle = "Menu Edit Menu Group";
        $data_menu_group = MenuGroup::find($id);
        $data_permission =  Permission::pluck('name', 'name')->all();
        return view('menu_group.edit', compact('data_menu_group', 'title', 'subtitle', 'data_permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MenuGroup  $menu_group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MenuGroup $menu_group): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'permission_name' => 'required',
            'status' => 'required',
            'position' => 'required',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'permission_name.required' => 'Nama Permission wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'position.required' => 'Urutan wajib diisi.',
        ]);
    
        // Menyimpan data lama sebelum update
        $oldMenuGroupData = $menu_group->toArray();
    
        // Melakukan update pada data MenuGroup
        $menu_group->update($request->all());
    
        // Mendapatkan ID pengguna yang sedang login
        $loggedInUserId = Auth::id();
    
        // Data log untuk penyimpanan
        $newMenuGroupData = $menu_group->toArray(); // Data baru setelah diperbarui
        $this->simpanLogHistori('Update', 'Menu Group', $menu_group->id, $loggedInUserId, json_encode($oldMenuGroupData), json_encode($newMenuGroupData));
    
        return redirect()->route('menu_groups.index')
            ->with('success', 'Menu Group berhasil diperbaharui');
    }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MenuGroup  $menu_group
     * @return \Illuminate\Http\Response
     */
   public function destroy($id)
    {
        $menu_group = MenuGroup::find($id);
        $menu_group->delete();
        $loggedInMenuGroupId = Auth::id();
        // Simpan log histori untuk operasi Delete dengan menu_group_id yang sedang login dan informasi data yang dihapus
        $this->simpanLogHistori('Delete', 'Menu Group', $id, $loggedInMenuGroupId, json_encode($menu_group), null);
        // Redirect kembali dengan pesan sukses
        return redirect()->route('menu_groups.index')->with('success', 'Menu Group berhasil dihapus');
    }
}
