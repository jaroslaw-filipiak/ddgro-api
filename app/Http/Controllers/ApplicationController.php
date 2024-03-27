<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Application;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationDataMail;
use Illuminate\Support\Facades\DB;


class ApplicationController extends Controller
{

    public function index()
    {
        $applications = Application::all();

        return response()->json([
            'status' => 200,
            'message' => 'Applications List',
            'data' => $applications
        ], 200);
    }

    public function store(Request $request)
    {

        // form validation

        $rules = [
            'type' => 'required',
            'total_area' => 'required',
            'count' => 'required',
            'lowest' => 'required',
            'highest' => 'required',
            // 'terrace_thickness' => 'required', grubość deski ==> wood
            // 'distance_between_joists' => 'required', Odległość pomiędzy legarami ==> wood
            // distance_between_supports_under_the_joist ==> wood
            // 'distance_between_supports' => 'required',
            // 'joist_height' => 'required', ==> wysokość legara ==> wood
            // 'slab_width' => 'required',
            // 'slab_height' => 'required',
            // 'slab_thickness' => 'required', grubość płyty ==>  slab
            'tiles_per_row' => 'required',
            'sum_of_tiles' => 'required',
            'support_type' => 'required',
            'main_system' => 'required',
            'name_surname' => 'required',
            'email' => 'required',
            'proffesion' => 'required',
            'terms_accepted' => 'required',
            'slabs_count' => 'required',
            'supports_count' => 'required',
            // 'products' => 'required|array|min:1',
            // 'accesories' => 'required|array|min:1',
            // 'additional_accessories' => 'required|array|min:1',
            // 'm_standard' => 'required|array|min:1',
        ];

        // Create a validator instance
        $validator = Validator::make($request->all(), $rules);

        // Conditionally add the 'required' rule for 'gap_between_slabs'
        $validator->sometimes('gap_between_slabs', 'required', function ($input) {
            return $input->type === 'slab';
        });

        // Conditionally add the 'required' rule for 'slab_thickness'
        $validator->sometimes('slab_thickness', 'required', function ($input) {
            return $input->type === 'slab';
        });

        // Conditionally add the 'required' rule for 'terrace_thickness'
        $validator->sometimes('terrace_thickness', 'required', function ($input) {
            return $input->type === 'wood';
        });

        // Conditionally add the 'required' rule for 'distance_between_joists'
        $validator->sometimes('distance_between_joists', 'required', function ($input) {
            return $input->type === 'wood';
        });

        // Conditionally add the 'required' rule for 'distance_between_supports_under_the_joist'
        $validator->sometimes('distance_between_supports_under_the_joist', 'required', function ($input) {
            return $input->type === 'wood';
        });

        // Conditionally add the 'required' rule for 'joist_height'
        $validator->sometimes('joist_height', 'required', function ($input) {
            return $input->type === 'wood';
        });

        // Conditionally add the 'required' rule for 'slab_width'
        $validator->sometimes('slab_width', 'required', function ($input) {
            return $input->type === 'slab';
        });

        // Conditionally add the 'required' rule for 'slab_height'
        $validator->sometimes('slab_height', 'required', function ($input) {
            return $input->type === 'slab';
        });



        // Check if the validation fails
        if ($validator->fails()) {
            // Return a JSON response with the validation errors
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()->all(),
            ], 422);
        }


        $data = $request->all();

        
        // Validation passed, so create a new application
        $application = Application::create($data);


        // Send email
        Mail::to('info@j-filipiak.pl')->send(new ApplicationDataMail($application));

        // Return a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Formularz został wysłany!',
            'application' => $application,

        ], 201);
    }

    public function show($id)
    {

        $application = Application::find($id);
        if ($application) {

            // 1. sprawdzam dominujacy system (standard, spiral, max)

            $dominant_system = $application->main_system;
            $dominant_system_matrix = '';
            
            switch ($dominant_system) {
                case 'standard':
                    $dominant_system_matrix = json_decode($application->m_standard);
                    break;
                case 'spiral':
                    $dominant_system_matrix = json_decode($application->m_spiral);
                    break;
                case 'max':
                    $dominant_system_matrix = json_decode($application->m_max);
                    break;
            };

            // 2. jaki jest min oraz max ?

            // $lowest = $application->lowest;
            // $highest = $application->highest;

            $lowest = 10;
            $highest = 2000;

            // 3. usuwam z tablic zakres, którege nie obsługuje dany typ systemu
            // 3a. m_standard

            $m_standard = json_decode($application->m_standard);
            $standard_wys_mm_min = 40;
            $standard_max_wys_mm_max = 420;
            
            $m_standard = array_filter(json_decode($application->m_standard), function ($item) use ($standard_max_wys_mm_max , $standard_wys_mm_min) {
                return $item->wys_mm <= $standard_max_wys_mm_max && $item->wys_mm >= $standard_wys_mm_min;
            });

            // 3b. m_max

            $m_max = json_decode($application->m_max);
            $max_wys_mm_min = 45;
            $max_wys_mm_max = 950;
            
            $m_max = array_filter(json_decode($application->m_max), function ($item) use ($max_wys_mm_max , $max_wys_mm_min) {
                return $item->wys_mm <= $max_wys_mm_max && $item->wys_mm >= $max_wys_mm_min;
            });

            // 3c. m_spiral

            $m_spiral = json_decode($application->m_spiral);
            $spiral_wys_mm_min = 10;
            $spiral_wys_mm_max = 210;
            
            $m_spiral = array_filter(json_decode($application->m_spiral), function ($item) use ($spiral_wys_mm_max , $spiral_wys_mm_min) {
                return $item->wys_mm <= $spiral_wys_mm_max && $item->wys_mm >= $spiral_wys_mm_min;
            });

            // first i want to get all products from dominant type that wys_mm is in range of lowest and highest

           
            switch ($dominant_system) {
                case 'standard':
                    $dominant = $m_standard;
                    break;
                case 'spiral':
                    $dominant = $m_spiral;
                    break;
                case 'max':
                    $dominant = $m_max;
                    break;
            };

            $products_dominant_system = array_filter($dominant, function ($item) use ($lowest, $highest) {
                return $item->wys_mm <= $highest && $item->wys_mm >= $lowest;
            });

            // ok i have all products from dominant type that wys_mm is in range of lowest and highest but i need get products from other types that are in range from lowest to highest but i want only to difference because i have only one type in order

            // 4. get products from other types that are in range from lowest to highest but i want only to difference because i have only one type in order

           
            $m_max_diff = array_diff_key($m_max, $products_dominant_system);
            $m_spiral_diff = array_diff_key($m_spiral, $products_dominant_system);
        
   
            return response()->json([
                'm_max_diff' => $m_max_diff,  
                'm_spiral_diff' => $m_spiral_diff,   
                'dominant_system' => $dominant_system,
                'lowest' => $lowest,
                'highest' => $highest,
                'dominant_system_matrix' => $dominant_system_matrix,
                'm_standard' => $m_standard,
                'm_max' => $m_max,
                'm_spiral' => $m_spiral,
                'products_dominant_system' => $products_dominant_system,
             
            
                // 'products_m_spiral'=> $products_m_spiral,
                // 'groupedMStandard' => $groupedMStandard,
                // 'groupedMSpiral' => $groupedMSpiral,
                // 'groupedMMax' => $groupedMMax,
                // 'filterRanges' =>  $filteredRanges,
                // 'filterRangesSpiral' =>  $filteredRangesSpiral,
                // 'filterRangesMax' =>  $filteredRangesMax,
                // 'products_for_selected_type' =>  $products,
                // 'm_spiral' => $m_spiral,
                //'dominant_series' => $dominant_series,
                //'keysStandard' => $keysStandard,
                //'keysSpiral' => $keysSpiral,
                //'keysMax' => $keysMax,
                //'order_m_standard' =>  $m_standard_products,
                //'order_m_spiral' =>  $m_spiral_products,
                //'order_m_max' =>  $m_max_products,
                // 'order_total_price' => $order_total_price,
                // 'order_total_price_spiral' => $order_total_price_spiral,
                // 'order_total_price_max' => $order_total_price_spiral,
                // 'how_many_items_in_order' =>  count($m_standard_products),
                //'summarize_m_standard' =>  $order_for_m_standard,
                //'summarize_m_spiral' =>  $order_for_m_spiral,
                //'summarize_m_max' =>  $order_for_m_max,
                //'data' => collect($application)->except('products')->except('accesories')->except('additional_accessories')->except('m_standard'),
                //'products' => json_decode($application->products),
               
                //'accesories' => json_decode($application->accesories),
                //'additional_accessories' =>  $filteredAccessories,
                //'m_standard' => json_decode($application->m_standard),
                //'m_spiral' => json_decode($application->m_spiral),
                //'m_max' => json_decode($application->m_max),
               

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