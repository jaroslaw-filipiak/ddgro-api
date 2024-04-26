<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Application;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicationDataMail;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{

    private function generatePdf($application)

    {
          // Generate PDF using application data
          $pdf = PDF::loadView('pdf-template', ['application' => $application]);

          return $pdf;
    }

    private function storePdf($pdf)
    {
        // Store PDF in storage
        $pdfPath = 'pdfs/' . uniqid() . '.pdf';
        Storage::put($pdfPath, $pdf->output());

        // Return path to stored PDF
        return $pdfPath;
    }

    private function sendEmail($application, $pdfPath)
    {
        // Send email with application data and PDF
        Mail::to('')->send(new ApplicationDataMail($application, $pdfPath));
    }

    public function index()
    {
        $applications = Application::all();
        $data = $applications->map(function ($item) {
            return collect($item)->except(['products', 'm_standard', 'm_spiral', 'm_max', 'accesories'])->toArray();
        });

        return response()->json([
            'status' => 200,
            'message' => 'Applications List',
            'data' =>  $data
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

        //from show i need to get the data to generate pdf
        $show = $this->show($application->id);

        // Generate PDF
        $pdf = PDF::loadView('pdf-template', ['application' => $show->original]);


        // Save the PDF to the storage
        $pdfPath = 'pdfs/' . uniqid() . '.pdf'; // Generate a unique filename

        // Storage::put($pdfPath, $pdf->output()); // Save the PDF to the storage
        Storage::put($pdfPath, $pdf->output()); // Save the PDF to the public storage


        // Generate link to the PDF
        //  $pdfUrl = Storage::url($pdfPath);
        $pdfUrl = env('APP_URL') . '/storage/app/' . $pdfPath;

        // Update the application with the PDF URL
        $application->pdf_url = $pdfUrl;
        $application->save();

        // Send email

        // $pdfUrl = Storage::url($pdfPath);
        Mail::to($application->email)->send(new ApplicationDataMail($application));


        // Return a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Formularz został wysłany!',
            'application' => $application,
            'show' => $show->original,

        ], 201);


    }

    public function show($id)
    {

        $application = Application::find($id);
        if ($application) {

            // prevent undefined variables

            $over_main_system_grouped_level_2 = 'initial';
            $over_main_system_summarized_level_2 = 'initial';
            $over_main_system_name = 'initial';
            $over_main_system_level_2_name = 'initial';
            $under_main_system_name = 'initial';
            $under_main_system_range = 'initial';
            $under_main_system_grouped = 'initial';
            $under_main_system_summarized= 'initial';

            $workRanges = [];

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


                // tutaj sprawdzam czy powyższy forach w ogóle wyrzucił jakiś wynik ponieważ
                // może się zdarzyć taka sytuacja, że klient w formularzu zaznaczy wartości min 300 max 600
                // i zaznaczy główny system spiral, który ma zakres od 10 do 210 a w minimum podanym prez klienta jest
                // 300 także po prostu będzie pusta tablica

                $has_atleast_one_item_in_main_system = false;

                foreach ($main_system_summarized as $range => $value) {
                    if ($value > 0) {
                        $has_atleast_one_item_in_main_system = true;
                        break;
                    }
                }

                if (!$has_atleast_one_item_in_main_system) {
                //   trzeba obsłużyć tą sytuację ale raczej na froncie nie przepuszcze tego formularza

                $systems = ['spiral','standard','max'];
                // TODO: validacja na froncie

                }


            // 5 check if there are any records under main system range


            // output range from $lowest to $main_system_range_min
            $diff_lowest = $main_system_range_min - $lowest;
            $under_main_system_range = [];
            $under_main_system_grouped = [];
            $under_main_system_summarized = [];

            if ($diff_lowest > 0 && $diff_lowest < $main_system_range_min) {

                switch($application->main_system) {
                    //  tak musi zostać to na przyszlośc ponieważ dojdą nowe systemy
                    case 'standard':
                        $under = $application->m_spiral;
                        $under_main_system_name = 'spiral';
                        break;
                    case 'spiral':
                        $under = $application->m_spiral;
                        $under_main_system_name = 'spiral';
                        break;
                    case 'max':
                        $under = $application->m_spiral;
                        $under_main_system_name = 'spiral';
                        break;
                }

                $under_main_system_range= collect(json_decode($under))->filter(function ($value, $key) use ($lowest, $main_system_range_min) {
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
            // TODO: przy over nie moge na pałe pobierać maxa z main system, bo moze byc inny i tak samo chyba przy min ?


            $diff_highest = $highest - $main_system_range_max;

            // dd($diff_highest);
            // dd($main_system_range_max);

            $over_main_system_range = [];
            $over_main_system_grouped = [];
            $over_main_system_summarized = [];


            // TODO: tu jest jakiś bug przy drugim warunku
            // if ($diff_highest > 0 && $diff_highest > $main_system_range_max)
            if ($diff_highest > 0 ) {

                switch($application->main_system) {
                    //  tak musi zostać to na przyszlośc ponieważ dojdą nowe systemy
                    case 'standard':
                        $over = $application->m_max;
                        $over_main_system_name = 'max';
                        $over_main_system_max = 950;
                        break;
                    case 'spiral':
                        $over = $application->m_standard;
                         $over_main_system_name = 'standard';
                         $over_main_system_max = 420;
                        break;
                    case 'max':
                        $over = $application->m_max;
                         $over_main_system_name = 'max';
                         $over_main_system_max = 420;
                        break;
                }

                $over_main_system_range= collect(json_decode($over))->filter(function ($value, $key) use ($main_system_range_max, $over_main_system_max) {
                    return $value->wys_mm > $main_system_range_max && $value->wys_mm <= $over_main_system_max;
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


                // scenaro if over_main_system_max is lower than highest
                // tu zawsze sprawdzasz wsponiki max jako najwyższy stopień

                $diff_highest_level_2 = $highest - $over_main_system_max;

                if($diff_highest_level_2 > 0) {
                    $over_main_system_range_level_2 = collect(json_decode($application->m_max))->filter(function ($value, $key) use ($over_main_system_max, $highest) {
                        return $value->wys_mm > $over_main_system_max && $value->wys_mm <= $highest;
                    });

                    $over_main_system_grouped_level_2 = [];

                    foreach ($over_main_system_range_level_2 as $object) {
                        $range = $object->range;
                        if (!isset($over_main_system_grouped_level_2[$range])) {
                            $over_main_system_grouped_level_2[$range] = [];
                        }
                        $over_main_system_grouped_level_2[$range][] = $object;
                    }

                    $over_main_system_summarized_level_2 = [];

                    foreach ($over_main_system_grouped_level_2 as $range => $objects) {
                        $count_in_range = 0;
                        foreach ($objects as $object) {
                            $count_in_range += $object->count_in_range;
                        }
                        $over_main_system_summarized_level_2[$range] = round($count_in_range);
                    }
                }

            }

           $cp1 = $main_system_summarized;
            $cp2 = $under_main_system_summarized;
            $cp3 = $over_main_system_summarized;


            //shit happens :)

            foreach ($main_system_summarized as $main_range => $main_count) {

                $temp = explode("-", $main_range);
                $min = $temp[0];
                $max = $temp[1];

                foreach ($under_main_system_summarized as $under_range => $under_count) {
                  $underTemp = explode("-", $under_range);
                  $underMin = $underTemp[0];
                  $underMax = $underTemp[1];

                  if ($underMin > $min || $max < $underMax) {
                      $under_main_system_summarized[$under_range] = $under_count - $main_count;
                  }
                }

                foreach ($over_main_system_summarized as $over_range => $over_count) {
                    $underTemp = explode("-", $over_range);
                    $underMin = $underTemp[0];
                    $underMax = $underTemp[1];

                    if ($max > $underMin) {
                        $over_main_system_summarized[$over_range] = $over_count - $main_count;
                    }
                }
            }

            // time to crate order

            $main_system_products = DB::table('products')
            ->where('type', $application->type)
            ->where('series', $application->main_system)
            ->get();

            $under_main_system_products = DB::table('products')
            ->where('type', $application->type)
            ->where('series', $under_main_system_name)
            ->get();

            $over_main_system_products = DB::table('products')
            ->where('type', $application->type)
            ->where('series', $over_main_system_name)
            ->get();




            // najwyzszy to zawsz jest max dla 'slab'
            $over_main_system_level_2_products = DB::table('products')
            ->where('type', $application->type)
            ->where('series', 'max')
            ->get();

            $order_for_main_system = [];
            $order_for_under_main_system = [];
            $order_for_over_main_system = [];
            $order_for_over_main_system_level_2 = [];


            // main system produkty + odrazu akcesoria dla main systemu

            /*
            TODO: bug if height_mm is not in main_system_summarized array
              +"height_mm": "45-75"
              array:7 [▼ // app\Http\Controllers\ApplicationController.php:472
                "30-50" => 436.0
                ]
            */


            foreach ($main_system_products as $product) {
                // Check if the "height_mm" attribute matches any key in the main_system_summarized array
                if (array_key_exists($product->height_mm, $main_system_summarized) && $main_system_summarized[$product->height_mm] > 0) {

                    // Add the count value corresponding to the product's height_mm to the product
                     $product->count = $main_system_summarized[$product->height_mm];

                     //Add total price
                     $product->total_price = number_format($product->price_net * $product->count, 2);

                    // Add the product to the filtered products array
                    $order_for_main_system[] = $product;
                }
            }

            $main_system_total = 0;

            // Loop through the array and calculate the sum of values
            // wiem ile jest wsporników łącznie z głównego systemu
            // znam nazwę głownego systemu : $application->main_system

            foreach ($main_system_summarized as $value) {
                $main_system_total += $value;
            }

                $accesories_width_fits_to_main_system = DB::table('accesories')->where('for_type', $application->type)->get();

                // Sample loop through accessories to find those compatible with the current main system
                foreach ($accesories_width_fits_to_main_system as $accessory) {

                    $fits_to_system = json_decode($accessory->fits_to_system);

                    // Check if the accessory is compatible with the current main system
                    if (in_array($application->main_system, $fits_to_system)) {
                        // Add the accessory to the list of accessories for the current main system
                        // add to each accesory count value that is equal to main_system_total
                        $accessory->count = $main_system_total;
                        $accessory->total_price = number_format($accessory->price_net * $accessory->count, 2);
                        $main_system_accesories[] = $accessory;
                    }
                }



                // if ($diff_lowest > 0 && $diff_lowest < $main_system_range_min) {
            if ($diff_lowest > 0 && $diff_lowest < $main_system_range_min) {


                foreach ($under_main_system_products as $product) {
                    // Check if the "height_mm" attribute matches any key in the under_main_system_summarized array
                    if (array_key_exists($product->height_mm, $under_main_system_summarized) && $under_main_system_summarized[$product->height_mm] > 0) {
                        // Add the count value corresponding to the product's height_mm to the product
                        $product->count = $under_main_system_summarized[$product->height_mm];

                         //Add total price
                        $product->total_price = number_format($product->price_net * $product->count, 2);

                        // Add the product to the filtered products array
                        $order_for_under_main_system[] = $product;
                    }
                }
                // =====

                $under_main_system_total = 0;

                // Loop through the array and calculate the sum of values
                // wiem ile jest wsporników łącznie z głównego systemu
                // znam nazwę głownego systemu : $application->main_system

                foreach ($under_main_system_summarized as $value) {
                    $under_main_system_total += $value;
                }

                    $accesories_width_fits_to_under_main_system = DB::table('accesories')->where('for_type', $application->type)->get();

                    // Sample loop through accessories to find those compatible with the current main system
                    foreach ($accesories_width_fits_to_under_main_system as $accessory) {

                        $fits_to_system = json_decode($accessory->fits_to_system);

                        // Check if the accessory is compatible with the current main system
                        if (in_array($under_main_system_name, $fits_to_system)) {
                            // Add the accessory to the list of accessories for the current main system
                            // add to each accesory count value that is equal to main_system_total
                            $accessory->count = $under_main_system_total;
                            $accessory->total_price = number_format($accessory->price_net * $accessory->count, 2);
                            $under_main_system_accesories[] = $accessory;
                        }
                    }


            }


            //TODO: tu jest jakiś bug przy drugim warunku
            // przed zmianami: if ($diff_highest > 0 && $diff_highest > $main_system_range_max) {
            // diff highest może miec minusową wartość
            /*
            if ($diff_highest > 0 && $diff_highest > $main_system_range_max) {
                wartośc minusowa w przypadku gdy np: lowest: 40  highest: 50 / main_system_min = 45 , main system max 950 czyli $lowest < $main_system_range_min
              */


            if ($diff_highest > 0 && $lowest > $main_system_range_min) {


                foreach ($over_main_system_products as $product) {
                    // Check if the "height_mm" attribute matches any key in the over_main_system_summarized array
                    if (array_key_exists($product->height_mm, $over_main_system_summarized) && $over_main_system_summarized[$product->height_mm] > 0) {
                        // Add the count value corresponding to the product's height_mm to the product
                        $product->count = $over_main_system_summarized[$product->height_mm];

                        //Add total price
                        $product->total_price = number_format($product->price_net * $product->count, 2);

                        // Add the product to the filtered products array
                        $order_for_over_main_system[] = $product;
                    }
                }

                // =====

                $over_main_system_total = 0;

                // Loop through the array and calculate the sum of values
                // wiem ile jest wsporników łącznie z głównego systemu
                // znam nazwę głownego systemu : $application->main_system

                foreach ($over_main_system_summarized as $value) {
                    $over_main_system_total += $value;
                }

                    $accesories_width_fits_to_over_main_system = DB::table('accesories')->where('for_type', $application->type)->get();

                    // Sample loop through accessories to find those compatible with the current main system
                    foreach ($accesories_width_fits_to_over_main_system as $accessory) {

                        $fits_to_system = json_decode($accessory->fits_to_system);

                        // Check if the accessory is compatible with the current main system
                        if (in_array($over_main_system_name, $fits_to_system)) {
                            // Add the accessory to the list of accessories for the current main system
                            // add to each accesory count value that is equal to main_system_total
                            $accessory->count = $over_main_system_total;
                            $accessory->total_price = number_format($accessory->price_net * $accessory->count, 2);
                            $over_main_system_accesories[] = $accessory;
                        }
                    }
            }

            if (isset($diff_highest_level_2) && $diff_highest_level_2 > 0) {
                foreach ($over_main_system_level_2_products as $product) {
                    // Check if the "height_mm" attribute matches any key in the over_main_system_summarized_level_2 array
                    if (array_key_exists($product->height_mm, $over_main_system_summarized_level_2) && $over_main_system_summarized_level_2[$product->height_mm] > 0) {
                        // Add the count value corresponding to the product's height_mm to the product
                        $product->count = $over_main_system_summarized_level_2[$product->height_mm];

                        //Add total price
                        $product->total_price = number_format($product->price_net * $product->count, 2);

                        // Add the product to the filtered products array
                        $order_for_over_main_system_level_2[] = $product;
                    }
                }

                $over_main_system_level_2_total = 0;

                // Loop through the array and calculate the sum of values
                // wiem ile jest wsporników łącznie z głównego systemu
                // znam nazwę głownego systemu : $application->main_system

                foreach ($over_main_system_summarized_level_2 as $value) {
                    $over_main_system_level_2_total += $value;
                }

                    $accesories_width_fits_to_over_main_system_summarized_level_2 = DB::table('accesories')->where('for_type', $application->type)->get();

                    // Sample loop through accessories to find those compatible with the current main system
                    foreach ($accesories_width_fits_to_over_main_system_summarized_level_2 as $accessory) {

                        $fits_to_system = json_decode($accessory->fits_to_system);

                        // Check if the accessory is compatible with the current main system
                        if (in_array($over_main_system_level_2_name, $fits_to_system)) {
                            // Add the accessory to the list of accessories for the current main system
                            // add to each accesory count value that is equal to main_system_total
                            $accessory->count = $over_main_system_level_2_total;
                            $accessory->total_price = number_format($accessory->price_net * $accessory->count, 2);
                            $over_main_system_level_2_accesories[] = $accessory;
                        }
                    }
            }

            //



            // summarize..
            $total = 0;

            // Summarize the price of products in $order_for_main_system
            foreach ($order_for_main_system as $product) {
                $total += $product->price_net * $product->count;
            }

            // Summarize the price of products in $order_for_under_main_system
            foreach ($order_for_under_main_system as $product) {
                $total += $product->price_net * $product->count;
            }

            // Summarize the price of products in $order_for_over_main_system
            foreach ($order_for_over_main_system as $product) {
                $total += $product->price_net * $product->count;
            }

            // Summarize the price of products in $order_for_over_main_system_level_2
            foreach ($order_for_over_main_system_level_2 as $product) {
                $total += $product->price_net * $product->count;
            }

            // Round the total to 2 decimal places
            $total = number_format($total, 2);

            return response()->json([
                // 'additional_accessories' => $accesories_for_selected_type,
                'accesories_width_fits_to_main_system' => $accesories_width_fits_to_main_system,
                'total' => $total,
                'order_for_main_system' => $order_for_main_system,
                'order_for_under_main_system' => $order_for_under_main_system,
                'order_for_over_main_system' => $order_for_over_main_system,
                'order_for_over_main_system_level_2' => $order_for_over_main_system_level_2,
                'main_system_products' => $main_system_products,
                'main_system_accesories' => $main_system_accesories,
                'has_items_in_main_system' => $has_atleast_one_item_in_main_system,
                'over_main_system_grouped_level_2' => $over_main_system_grouped_level_2,
                'over_main_system_summarized_level_2' => $over_main_system_summarized_level_2,
                'lowest' => $lowest,
                'highest' => $highest,
                'diff_lowest' => $diff_lowest,
                'diff_highest' => $diff_highest,
                'main_system_name' => $application->main_system,
                'over_main_system_name' => $over_main_system_name,
                'over_main_system_accesories' => isset($over_main_system_accesories) ? $over_main_system_accesories : [],
                'over_main_system_level_2_name' => 'max',
                'over_main_system_level_2_accesories'=> isset($over_main_system_level_2_accesories) ? $over_main_system_level_2_accesories : [],
                'main_system' => $main_system,
                'main_system_grouped' => $main_system_grouped,
                'main_system_summarized' => $main_system_summarized,
                'under_main_system_name' => $under_main_system_name,
                'under_main_system_accesories' => isset($under_main_system_accesories) ? $under_main_system_accesories : [],
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
