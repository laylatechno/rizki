<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\MenuGroup;
use App\Models\MenuItem;
use App\Models\Permission;
use App\Models\Route;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MenuItemsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:menuitem-list|menuitem-create|menuitem-edit|menuitem-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:menuitem-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:menuitem-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:menuitem-delete', ['only' => ['destroy']]);
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
        $positions = $request->input('positions'); // ID menu yang telah diurutkan
        $parentId = $request->input('parent_id'); // ID parent (submenu)
    
        // Perbarui posisi untuk item menu yang diurutkan
        foreach ($positions as $index => $itemId) {
            $menu_item = MenuItem::find($itemId);
    
            if ($menu_item) {
                if ($menu_item->parent_id === null) { // Menu utama
                    // Perbarui posisi untuk menu utama
                    $menu_item->position = $index + 1;
                    $menu_item->save();
                } elseif ($menu_item->parent_id == $parentId) { // Submenu
                    // Perbarui posisi untuk submenu
                    $menu_item->position = $index + 1;
                    $menu_item->save();
                }
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
        $title = "Halaman Menu Item";
        $subtitle = "Menu Menu Item";
        
        $query = MenuItem::orderBy('position', 'asc');
    
        // Check if search input exists
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', '%' . $search . '%');
        }
    
        $data_menu_item = $query->paginate(30);
    
        return view('menu_item.index', compact('data_menu_item', 'title', 'subtitle'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Menu Item";
        $subtitle = "Menu Tambah Menu Item";
        $data_menu_group = MenuGroup::pluck('name', 'id')->all();
        $data_permission = Permission::pluck('name', 'id')->all();
        $data_menu_items = MenuItem::where('status', 'Aktif')->pluck('name', 'id')->all();
        
        // Ambil data routes berdasarkan nama
        $data_routes = Route::pluck('name', 'name')->all();
    
        return view('menu_item.create', compact('title', 'subtitle', 'data_permission', 'data_menu_group', 'data_menu_items', 'data_routes'));
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
            'name' => 'required|unique:menu_items,name',
            'permission_name' => 'required',
            'status' => 'required',
            'icon' => 'required',
            'route' => 'required',
            'position' => 'required|integer',
            'menu_group_id' => 'required|exists:menu_groups,id',
            'parent_id' => 'nullable|exists:menu_items,id', // Validasi parent_id bisa null atau harus ID yang valid di menu_items
        ], [
            'name.required' => 'Nama wajib diisi.',
            'icon.required' => 'Icon wajib diisi.',
            'route.required' => 'Route wajib diisi.',
            'name.unique' => 'Nama sudah terdaftar.',
            'permission_name.required' => 'Nama Permission wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'position.required' => 'Urutan wajib diisi.',
            'menu_group_id.required' => 'Menu Group wajib dipilih.',
            'menu_group_id.exists' => 'Menu Group tidak ditemukan.',
            'parent_id.exists' => 'Parent Menu tidak valid.',
        ]);

        $newMenuItem = MenuItem::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'route' => $request->route,
            'permission_name' => $request->permission_name,
            'status' => $request->status,
            'position' => $request->position,
            'menu_group_id' => $request->menu_group_id,
            'parent_id' => $request->parent_id, // Menyimpan ID parent
        ]);

        $loggedInUserId = Auth::id();
        $this->simpanLogHistori('Create', 'Menu Item', $newMenuItem->id, $loggedInUserId, null, json_encode($newMenuItem->toArray()));

        return redirect()->route('menu_items.index')
            ->with('success', 'Menu Item berhasil dibuat.');
    }





    /**
     * Display the specified resource.
     *
     * @param  \App\MenuItem  $menu_item
     * @return \Illuminate\Http\Response
     */
    public function show($id): View

    {
        $title = "Halaman Lihat Menu Item";
        $subtitle = "Menu Lihat Menu Item";
        $data_menu_item = MenuItem::find($id);
        $data_permission =  Permission::pluck('name', 'name')->all();
        $data_menu_group =  MenuGroup::pluck('name', 'id')->all();
        return view('menu_item.show', compact('data_menu_item', 'title', 'subtitle', 'data_permission', 'data_menu_group'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MenuItem  $menu_item
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Menu Item";
        $subtitle = "Menu Edit Menu Item";
        $data_menu_item = MenuItem::findOrFail($id); // Data menu item yang sedang diedit
        $data_permission = Permission::pluck('name', 'name')->all();
        $data_menu_group = MenuGroup::pluck('name', 'id')->all();
        $data_menu_items = MenuItem::where('id', '!=', $id)->pluck('name', 'id')->all(); // Mengambil semua menu item kecuali item yang sedang diedit
        $data_routes = Route::pluck('name', 'name')->all(); // Mengambil semua route dari tabel 'routes'
    
        return view('menu_item.edit', compact('data_menu_item', 'title', 'subtitle', 'data_permission', 'data_menu_group', 'data_menu_items', 'data_routes'));
    }
    


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MenuItem  $menu_item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MenuItem $menu_item): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required|unique:menu_items,name,' . $menu_item->id,
            'permission_name' => 'required',
            'status' => 'required',
            'icon' => 'required',
            'route' => 'required',
            'position' => 'required|integer',
            'menu_group_id' => 'required|exists:menu_groups,id',
            'parent_id' => 'nullable|exists:menu_items,id', // Validasi parent_id bisa null atau ID yang valid di menu_items
        ], [
            'name.required' => 'Nama wajib diisi.',
            'icon.required' => 'Icon wajib diisi.',
            'route.required' => 'Route wajib diisi.',
            'name.unique' => 'Nama sudah terdaftar.',
            'permission_name.required' => 'Nama Permission wajib diisi.',
            'status.required' => 'Status wajib diisi.',
            'position.required' => 'Urutan wajib diisi.',
            'menu_group_id.required' => 'Menu Group wajib dipilih.',
            'menu_group_id.exists' => 'Menu Group tidak ditemukan.',
            'parent_id.exists' => 'Parent Menu tidak valid.',
        ]);

        $oldMenuItemData = $menu_item->toArray();

        $menu_item->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'route' => $request->route,
            'permission_name' => $request->permission_name,
            'status' => $request->status,
            'position' => $request->position,
            'menu_group_id' => $request->menu_group_id,
            'parent_id' => $request->parent_id, // Menyimpan ID parent
        ]);

        $loggedInUserId = Auth::id();
        $this->simpanLogHistori(
            'Update',
            'Menu Item',
            $menu_item->id,
            $loggedInUserId,
            json_encode($oldMenuItemData),
            json_encode($menu_item->toArray())
        );

        return redirect()->route('menu_items.index')
            ->with('success', 'Menu Item berhasil diperbaharui');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MenuItem  $menu_item
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menu_item = MenuItem::find($id);
        $menu_item->delete();
        $loggedInMenuItemId = Auth::id();
        // Simpan log histori untuk operasi Delete dengan menu_item_id yang sedang login dan informasi data yang dihapus
        $this->simpanLogHistori('Delete', 'Menu Item', $id, $loggedInMenuItemId, json_encode($menu_item), null);
        // Redirect kembali dengan pesan sukses
        return redirect()->route('menu_items.index')->with('success', 'Menu Item berhasil dihapus');
    }
}
