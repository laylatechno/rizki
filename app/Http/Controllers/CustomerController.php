<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\Customer;
use App\Models\CustomerCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:customer-list|customer-create|customer-edit|customer-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:customer-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:customer-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:customer-delete', ['only' => ['destroy']]);
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
        $title = "Halaman Pelanggan";
        $subtitle = "Menu Pelanggan";
        $data_customers = Customer::all();
        return view('customer.index', compact('data_customers', 'title', 'subtitle'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $title = "Halaman Tambah Pelanggan";
        $subtitle = "Menu Tambah Pelanggan";
        $data_customer_categories = CustomerCategory::all();
        return view('customer.create', compact('title', 'subtitle', 'data_customer_categories'));
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
            'name' => 'required|unique:customers,name',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'name.unique' => 'Nama sudah terdaftar.',
        ]);

        $customer = Customer::create($request->all());

        $loggedInUserId = Auth::id();
        // Simpan log histori untuk operasi Create dengan user_id yang sedang login
        $this->simpanLogHistori('Create', 'Customer', $customer->id, $loggedInUserId, null, json_encode($customer));
        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil dibuat.');
    }





    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show($id): View

    {
        $title = "Halaman Lihat Pelanggan";
        $subtitle = "Menu Lihat Pelanggan";
        $data_customers = Customer::find($id);
        return view('customer.show', compact('data_customers', 'title', 'subtitle'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $title = "Halaman Edit Pelanggan";
        $subtitle = "Menu Edit Pelanggan";
        $data_customer_categories = CustomerCategory::all();
        $data_customers = Customer::with('category')->findOrFail($id); // Memuat relasi category

        return view('customer.edit', compact('data_customers', 'title', 'subtitle', 'data_customer_categories'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
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
        $customer = Customer::find($id);

        // Jika data tidak ditemukan
        if (!$customer) {
            return redirect()->route('customers.index')
                ->with('error', 'Data Customer tidak ditemukan.');
        }

        // Menyimpan data lama sebelum update
        $oldCustomersnData = $customer->toArray();

        // Melakukan update data
        $customer->update($request->all());

        // Mendapatkan ID pengguna yang sedang login
        $loggedInUserId = Auth::id();

        // Mendapatkan data baru setelah update
        $newCustomersnData = $customer->fresh()->toArray();

        // Menyimpan log histori untuk operasi Update
        $this->simpanLogHistori('Update', 'Customer', $customer->id, $loggedInUserId, json_encode($oldCustomersnData), json_encode($newCustomersnData));

        return redirect()->route('customers.index')
            ->with('success', 'Pelanggan berhasil diperbaharui');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $customer = Customer::find($id);
        $customer->delete();
        $loggedInCustomerId = Auth::id();
        // Simpan log histori untuk operasi Delete dengan customer_id yang sedang login dan informasi data yang dihapus
        $this->simpanLogHistori('Delete', 'Customer', $id, $loggedInCustomerId, json_encode($customer), null);
        // Redirect kembali dengan pesan sukses
        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus');
    }
}
