<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class TutorialController extends Controller
{
    protected $filePath;

    public function __construct()
    {
        // Set file path untuk menyimpan status tutorial
        $this->filePath = storage_path('app/tutorial_setting.json');
    }

    // Membaca status tutorial dari file JSON
    public function getTutorialStatus()
    {
        // Jika file JSON ada, baca statusnya
        if (File::exists($this->filePath)) {
            $data = json_decode(File::get($this->filePath), true);
            return response()->json($data);
        }

        // Status default jika file tidak ada
        return response()->json(['tutorialClosed' => false]);
    }

    // Menyimpan status tutorial ke dalam file JSON
    public function setTutorialStatus(Request $request)
    {
        // Ambil status dari request
        $status = $request->input('tutorialClosed');
        $data = ['tutorialClosed' => $status];

        // Simpan status tutorial ke dalam file JSON
        File::put($this->filePath, json_encode($data, JSON_PRETTY_PRINT));

        return response()->json(['message' => 'Status tutorial updated']);
    }
}
