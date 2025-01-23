<?php

namespace App\Http\Controllers;

use App\Models\LogHistori;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogHistoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     function __construct()
     {
         $this->middleware('permission:loghistori-list|loghistori-create|loghistori-edit|loghistori-delete', ['only' => ['index', 'store']]);
         $this->middleware('permission:loghistori-create', ['only' => ['create', 'store']]);
         $this->middleware('permission:loghistori-edit', ['only' => ['edit', 'update']]);
         $this->middleware('permission:loghistori-delete', ['only' => ['destroy']]);
     }


    public function index()
    {
        $title = "Halaman Log Histori";
        $subtitle = "Menu Log Histori";
        $data_log_histori = LogHistori::orderBy('id', 'desc')->get();
        return view('log_histori',compact('data_log_histori','title','subtitle'));
    }

    public function deleteAll()
    {
        try {
            // Use DB facade to perform a raw SQL delete query
            DB::statement('DELETE FROM log_histories');
            
            // Redirect back with a success message
            return redirect()->route('log_histori.index')->with('success', 'Semua data log histori berhasil dihapus.');
        } catch (\Exception $e) {
            // Redirect back with an error message if something goes wrong
            return redirect()->route('log_histori.index')->with('error', 'Failed to delete data. Please try again.');
        }
    }
 

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function update(Request $request, $id)
     {
         
     }
     
     
     
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         
    }

}