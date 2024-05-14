<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\AccesoriesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Accesories;

class AccesoriesController extends Controller
{
    public function import()
    {
        Excel::import(new AccesoriesImport, 'accesories.xlsx');

        return redirect('/')->with('success', 'All good!');


    }

    public function index()
    {
        $accesories = Accesories::all();

        return response()->json([
            'status' => 200,
            'message' => 'Accesories List',
            'data' => $accesories
        ], 200);
    }

    public function get_wood_accesories()
    {
        $accesories = Accesories::where('for_type', 'wood')->get();

        return response()->json([
            'status' => 200,
            'message' => 'Wood Accesories List',
            'data' => $accesories
        ], 200);
    }

    public function get_slab_accesories()
    {
        $accesories = Accesories::where('for_type', 'slab')->get();

        return response()->json([
            'status' => 200,
            'message' => 'Slab Accesories List',
            'data' => $accesories
        ], 200);
    }
}
