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
}
