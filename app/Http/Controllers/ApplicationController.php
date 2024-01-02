<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    //

    public function application(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            // ...
        ]);

        // return response()->json([
        //     'status' => 200,
        //     'message' => 'Application',
        //     'data' => $request
        // ], 200);
    }
}
