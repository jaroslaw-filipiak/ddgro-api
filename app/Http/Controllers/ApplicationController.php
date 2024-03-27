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

            $additionalAccessories = json_decode($application->additional_accessories);

            $filteredAccessories = array_filter(json_decode($application->accesories), function ($accessory) use ($additionalAccessories) {
                return in_array($accessory->id, $additionalAccessories);
            });
            
            // Group objects in m... where 'range' value is equal
            $m_standard = json_decode($application->m_standard);
            $m_spiral = json_decode($application->m_spiral);
            $m_max = json_decode($application->m_max);

            $groupedMStandard = [];
            $groupedMSpiral = [];
            $groupedMMax = [];

            foreach ($m_standard as $object) {
                $range = $object->range;
                if (!isset($groupedMStandard[$range])) {
                    $groupedMStandard[$range] = [];
                }
                $groupedMStandard[$range][] = $object;
            }


            foreach ($m_spiral as $object) {
                $range = $object->range;
                if (!isset($groupedMSpiral[$range])) {
                    $groupedMSpiral[$range] = [];
                }
                $groupedMSpiral[$range][] = $object;
            }
            foreach ($m_max as $object) {
                $range = $object->range;
                if (!isset($groupedMMax[$range])) {
                    $groupedMMax[$range] = [];
                }
                $groupedMMax[$range][] = $object;
            }


            
            // Update $m_standard with grouped objects
            $m_standard = $groupedMStandard;
            $m_spiral = $groupedMSpiral;
            $m_max = $groupedMMax;


            // ranges
        
            $filteredRanges = [];
            $filteredRangesSpiral = [];
            $filteredRangesMax = [];

          

            foreach ($groupedMStandard as $range => $items) {
                $filteredItems = array_filter($items, function ($item) {
                    return $item->condition == 1;
                });

                $filteredRanges[$range] = array_values($filteredItems);
            }

        
            foreach ($groupedMSpiral as $range => $items) {
                $filteredItemsSpiral = array_filter($items, function ($item) {
                    return $item->condition == 1;
                });

                $filteredRangesSpiral[$range] = array_values($filteredItemsSpiral);
            }

            foreach ($groupedMMax as $range => $items) {
                $filteredItemsMax = array_filter($items, function ($item) {
                    return $item->condition == 1;
                });

                $filteredRangesMax[$range] = array_values($filteredItemsMax);
            }


           
            // Specify the keys to remove from $filteredRangesSpiral
            // spiral 10 - 210
            // standard 30 - 420
            // max 45 - 950
            //TODO: spiral zobacz rangesy , brakuje jednego oraz wyrzuca klucz ""

            $keysToRemoveFromStandard = ['10-17', '17-30', '350-550', '550-750', '750-950'];
            $keysToRemoveFromSpiral = ['750-950', '550-750', '350-550', "220-320", "320-420" , ""];
            $keysToRemoveFromMax = ['10-17', '17-30', ];

            
            // Remove the specified keys from M_STANDARD ==> $filteredRanges
            foreach ($keysToRemoveFromStandard as $key) {
                unset($filteredRanges[$key]);
            }
           
            // Remove the specified keys from M_SPIRAL ==> $filteredRangesSpiral
            foreach ($keysToRemoveFromSpiral as $key) {
                unset($filteredRangesSpiral[$key]);
            }

            // Remove the specified keys from M_MAX ==> $filteredRangesMax
            foreach ($keysToRemoveFromMax as $key) {
                unset($filteredRangesMax[$key]);
            }

        
            // summarize m_standard
            $order_for_m_standard = [];
            $order_for_m_spiral = [];
            $order_for_m_max = [];

            foreach ($filteredRanges as $range => $objects) {
                $count_in_range = 0;
                foreach ($objects as $object) {
                    $count_in_range += $object->count_in_range;
                }
                $order_for_m_standard[$range] = round($count_in_range);
            }

           
            foreach ($filteredRangesSpiral as $range => $objects) {
                $count_in_range = 0;
                foreach ($objects as $object) {
                    $count_in_range += $object->count_in_range;
                }
                $order_for_m_spiral[$range] = round($count_in_range);
               
            }

            foreach ($filteredRangesMax as $range => $objects) {
                $count_in_range = 0;
                foreach ($objects as $object) {
                    $count_in_range += $object->count_in_range;
                }
                $order_for_m_max[$range] = round($count_in_range);
            }

            $m_standard_products = [];
            $m_spiral_products = [];
            $m_max_products = [];

            // Filter products based on height_mm
            // 1. take all products for selected application type

            $products = DB::table('products')
            ->where('type', $application->type)
            ->get();

                $products_m_standard = DB::table('products')
                ->where('type', $application->type)
                ->where('series', 'standard')
                ->get();    

                $products_m_spiral = DB::table('products')
                ->where('type', $application->type)
                ->where('series', 'spiral')
                ->get();   

                $products_m_max = DB::table('products')
                ->where('type', $application->type)
                ->where('series', 'max')
                ->get();   


            // 2. filter products based on height_mm
            $keysStandard = array_keys(array_filter($order_for_m_standard, function ($value) {
                return $value > 0;
            }));

            $keysSpiral = array_keys(array_filter($order_for_m_spiral, function ($value) {
                return $value > 0;
            }));

            $keysMax = array_keys(array_filter($order_for_m_max, function ($value) {
                return $value > 0;
            }));

            foreach ($products_m_standard as $object) {
                if (in_array($object->height_mm, $keysStandard)) {
                    $m_standard_products[] = $object;
                }
            }

            
            foreach ($products_m_spiral as $object) {
                if (in_array($object->height_mm, $keysSpiral)) {
                    $m_spiral_products[] = $object;
                }
            }

            foreach ($products_m_max as $object) {
                if (in_array($object->height_mm, $keysMax)) {
                    $m_max_products[] = $object;
                }
            }

            // to each product add count based on height_mm
            foreach ($m_standard_products as $product) {
                $product->count = $order_for_m_standard[$product->height_mm];
                // to each product add count based on height_mm and calculate total
                $product->total = number_format( $product->price_net * $product->count, 2);
            }

             // to each product add count based on height_mm
             foreach ($m_spiral_products as $product) {
                $product->count = $order_for_m_spiral[$product->height_mm];
                $product->total = number_format( $product->price_net * $product->count, 2);
            }

             // to each product add count based on height_mm
             foreach ($m_max_products as $product) {
                $product->count = $order_for_m_max[$product->height_mm];
                $product->total = number_format( $product->price_net * $product->count, 2);
            }

            //  total price

            $order_total_price = 0;
            $order_total_price_spiral = 0;
            $order_total_price_max = 0;
            

           
            // TODO : Fix calculations problem
            foreach ($m_standard_products as $product) {
                $order_total_price += $product->price_net * $product->count;
            }
         
            foreach ($m_spiral_products as $product) {
                $order_total_price_spiral += $product->price_net * $product->count;
            }
         
            foreach ($m_max_products as $product) {
                $order_total_price_max += $product->price_net * $product->count;
            }

            $order_total_price = number_format($order_total_price, 2);
            $order_total_price_spiral = number_format($order_total_price_spiral, 2);
            $order_total_price_max = number_format($order_total_price_max, 2);

            // check main system 
            $dominant_series = $application->main_system;

            // check lowest and highest range for full order
            $lowest = $application->lowest;
            $highest = $application->highest;

           

            return response()->json([
                // 'products_m_spiral'=> $products_m_spiral,
                // 'groupedMStandard' => $groupedMStandard,
                // 'groupedMSpiral' => $groupedMSpiral,
                // 'groupedMMax' => $groupedMMax,
                // 'filterRanges' =>  $filteredRanges,
                // 'filterRangesSpiral' =>  $filteredRangesSpiral,
                // 'filterRangesMax' =>  $filteredRangesMax,
                // 'products_for_selected_type' =>  $products,
                // 'm_spiral' => $m_spiral,
                'dominant_series' => $dominant_series,
                'keysStandard' => $keysStandard,
                'keysSpiral' => $keysSpiral,
                'keysMax' => $keysMax,
                'order_m_standard' =>  $m_standard_products,
                'order_m_spiral' =>  $m_spiral_products,
                'order_m_max' =>  $m_max_products,
                // 'order_total_price' => $order_total_price,
                // 'order_total_price_spiral' => $order_total_price_spiral,
                // 'order_total_price_max' => $order_total_price_spiral,
                // 'how_many_items_in_order' =>  count($m_standard_products),
                'summarize_m_standard' =>  $order_for_m_standard,
                'summarize_m_spiral' =>  $order_for_m_spiral,
                'summarize_m_max' =>  $order_for_m_max,
                'data' => collect($application)->except('products')->except('accesories')->except('additional_accessories')->except('m_standard'),
                'products' => json_decode($application->products),
               
                'accesories' => json_decode($application->accesories),
                'additional_accessories' =>  $filteredAccessories,
                'm_standard' => json_decode($application->m_standard),
                'm_spiral' => json_decode($application->m_spiral),
                'm_max' => json_decode($application->m_max),
               

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