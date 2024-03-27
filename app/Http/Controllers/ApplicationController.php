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


            // 1. Get lowest + highest
            $lowest = $application->lowest;
            $highest = $application->highest;

        
            // 2. Get the products from main_system 

            switch($application->main_system) {
                case 'standard':
                    $main_system = json_decode($application->m_standard);
                    $main_system_range_min = 30;
                    $main_system_range_max = 420;
                    break;
                case 'spiral':
                    $main_system = json_decode($application->m_spiral);
                    $main_system_range_min = 10;
                    $main_system_range_max = 210;
                    break;
                case 'max':
                    $main_system = json_decode($application->m_max);
                    $main_system_range_min = 45;
                    $main_system_range_max = 950;
                    break;
            }

            // 3. take from #main_system all records that wys_mm is between $main_system_range_min and $main_system_range_max
            $main_system = collect($main_system)->filter(function ($value, $key) use ($main_system_range_min, $main_system_range_max) {
                return $value->wys_mm >= $main_system_range_min && $value->wys_mm <= $main_system_range_max;
            });

            // 4 main system grouped and summarized

            $main_system_grouped = [];
            $main_system_summarized = [];

            foreach ($main_system as $object) {
                $range = $object->range;
                if (!isset($main_system_grouped[$range])) {
                    $main_system_grouped[$range] = [];
                }
                $main_system_grouped[$range][] = $object;
            }

            foreach ($main_system_grouped as $range => $objects) {
                $count_in_range = 0;
                foreach ($objects as $object) {
                    $count_in_range += $object->count_in_range;
                }
                $main_system_summarized[$range] = round($count_in_range);
            }


            // 5 check if there are any records under main system range


            // output range from $lowest to $main_system_range_min
            $diff_lowest = $main_system_range_min - $lowest;

            if ($diff_lowest > 0 && $diff_lowest < $main_system_range_min) {

                $under_main_system_range= collect(json_decode($application->m_spiral))->filter(function ($value, $key) use ($lowest, $main_system_range_min) {
                    return $value->wys_mm >= $lowest && $value->wys_mm < $main_system_range_min;
                });

                $under_main_system_grouped = [];

                foreach ($under_main_system_range as $object) {
                    $range = $object->range;
                    if (!isset($under_main_system_grouped[$range])) {
                        $under_main_system_grouped[$range] = [];
                    }
                    $under_main_system_grouped[$range][] = $object;
                }
    
                $under_main_system_summarized = [];
    
                foreach ($under_main_system_grouped as $range => $objects) {
                    $count_in_range = 0;
                    foreach ($objects as $object) {
                        $count_in_range += $object->count_in_range;
                    }
                    $under_main_system_summarized[$range] = round($count_in_range);
                }
                
            };

            // 6 check if there are any records higher than main system range

            $diff_highest = $highest - $main_system_range_max;

            if ($diff_highest > 0 && $diff_highest < $main_system_range_max) {

                $over_main_system_range= collect(json_decode($application->m_max))->filter(function ($value, $key) use ($main_system_range_max, $highest) {
                    return $value->wys_mm > $main_system_range_max && $value->wys_mm <= $highest;
                });

                $over_main_system_grouped = [];

                foreach ($over_main_system_range as $object) {
                    $range = $object->range;
                    if (!isset($over_main_system_grouped[$range])) {
                        $over_main_system_grouped[$range] = [];
                    }
                    $over_main_system_grouped[$range][] = $object;
                }
    
                $over_main_system_summarized = [];
    
                foreach ($over_main_system_grouped as $range => $objects) {
                    $count_in_range = 0;
                    foreach ($objects as $object) {
                        $count_in_range += $object->count_in_range;
                    }
                    $over_main_system_summarized[$range] = round($count_in_range);
                }
                
            }


            // under main system grouped and summarized

           
            return response()->json([

                'lowest' => $lowest,
                'highest' => $highest,
                'diff_lowest' => $diff_lowest,
                'diff_highest' => $diff_highest,
                'main_system' => $main_system,
                'main_system_grouped' => $main_system_grouped,
                'main_system_summarized' => $main_system_summarized,
                'under_main_system_range' => $under_main_system_range,
                'under_main_system_grouped' => $under_main_system_grouped,
                'under_main_system_summarized' => $under_main_system_summarized,
                'over_main_system_range' => $over_main_system_range,
                'over_main_system_grouped' => $over_main_system_grouped,
                'over_main_system_summarized' => $over_main_system_summarized,
                'main_system_range_min' => $main_system_range_min,
                'main_system_range_max' => $main_system_range_max,
               
                 'data' => collect($application)->except('products')->except('accesories')->except('additional_accessories')->except('m_standard'),

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