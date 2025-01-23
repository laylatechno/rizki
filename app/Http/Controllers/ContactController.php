<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:contact-list|contact-create|contact-edit|contact-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:contact-delete', ['only' => ['destroy']]);
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
        $title = "Halaman Kontak";
        $subtitle = "Menu Kontak";
        $data_contacts = Contact::all();
        return view('contact.index', compact('data_contacts', 'title', 'subtitle'));
    }


 
 



    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show($id): View

    {
        $title = "Halaman Lihat Kontak";
        $subtitle = "Menu Lihat Kontak";
        $data_contacts = Contact::find($id);
        return view('contact.show', compact('data_contacts', 'title', 'subtitle'));
    }

 

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $contact = Contact::find($id);
        $contact->delete();
        $loggedInContactId = Auth::id();
        // Simpan log histori untuk operasi Delete dengan contact_id yang sedang login dan informasi data yang dihapus
        $this->simpanLogHistori('Delete', 'Kontak', $id, $loggedInContactId, json_encode($contact), null);
        // Redirect kembali dengan pesan sukses
        return redirect()->route('contacts.index')->with('success', 'Kontak berhasil dihapus');
    }
}
