<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    public function import()
    {
        Excel::import(new ProductsImport, 'products.xlsx');

        return redirect('/')->with('success', 'All good!');
    }

    public function index()
    {
        $products = Product::all();

        return response()->json([
            'status' => 200,
            'message' => 'Products List',
            'data' => $products
        ], 200);
    }

    public function show($id)
    {

        $product = Product::find($id);
        if ($product) {

            return response()->json([
                'status' => 200,
                'data' => $product
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Product Not Found',
                'data' => []
            ], 404);
        }
    }

    public function show_by_series($name)
    {

        $products = DB::table('products')->where('series', $name)->get();

        if ($products) {

            return response()->json([
                'status' => 200,
                'data' => $products
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Products Not Found',
                'data' => []
            ], 404);
        }
    }

    public function edit($id)
    {
        $product = Product::find($id);
        if ($product) {

            return response()->json([
                'status' => 200,
                'data' => $product
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Product Not Found',
                'data' => []
            ], 404);
        }
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'series' => 'required',
            'type' => 'required',
            'distance_code' => 'required',
            'distance_min' => 'required',
            'distance_max' => 'required',
            'photo' => 'required',
            'name' => 'required',
            'description' => 'required',
            'short_name' => 'required',
            'height_mm' => 'required',
            'height_inch' => 'required',
            'packaging' => 'required',
            'euro_palet' => 'required',
            'price_net' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Bad Request',
                'data' => $validator->messages()
            ], 422);
        } else {

            $product = Product::find($id);

            if ($product) {

                $product->update([
                    'series' => $request->series,
                    'type' => $request->type,
                    'distance_code' => $request->distance_code,
                    'distance_min' => $request->distance_min,
                    'distance_max' => $request->distance_max,
                    'photo' => $request->photo,
                    'name' => $request->name,
                    'description' => $request->description,
                    'short_name' => $request->short_name,
                    'height_mm' => $request->height_mm,
                    'height_inch' => $request->height_inch,
                    'packaging' => $request->packaging,
                    'euro_palet' => $request->euro_palet,
                    'price_net' => $request->price_net,

                ]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Product Updated',
                    'data' => $product
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Product Not Found',
                    'data' => []
                ], 404);
            }
        }
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'series' => 'required',
            'type' => 'required',
            'distance_code' => 'required',
            'distance_min' => 'required',
            'distance_max' => 'required',
            'photo' => 'required',
            'name' => 'required',
            'description' => 'required',
            'short_name' => 'required',
            'height_mm' => 'required',
            'height_inch' => 'required',
            'packaging' => 'required',
            'euro_palet' => 'required',
            'price_net' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Bad Request',
                'data' => $validator->messages()
            ], 422);
        } else {
            $product = Product::create([
                'series' => $request->series,
                'type' => $request->type,
                'distance_code' => $request->distance_code,
                'distance_min' => $request->distance_min,
                'distance_max' => $request->distance_max,
                'photo' => $request->photo,
                'name' => $request->name,
                'description' => $request->description,
                'short_name' => $request->short_name,
                'height_mm' => $request->height_mm,
                'height_inch' => $request->height_inch,
                'packaging' => $request->packaging,
                'euro_palet' => $request->euro_palet,
                'price_net' => $request->price_net,

            ]);

            if ($product) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Product Created',
                    'data' => $product
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Internal Server Error',
                    'data' => []
                ], 500);
            }
        }
    }

    public function delete($id)
    {
        $product = Product::find($id);

        if ($product) {
            $product->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Product Deleted',

            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Product Not Found',
                'data' => []
            ], 404);
        }
    }
}
