<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AccesoriesController;
use App\Http\Controllers\ApplicationController;
use App\Models\Application;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\ApplicationDataMail;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('products/import', [ProductController::class, 'import']);
// Route::get('accesories/import', [AccesoriesController::class, 'import']);


Route::get('/mailable', function () {
    
    $application = App\Models\Application::find(4);

    return new App\Mail\ApplicationDataMail($application);
});

Route::get('/mailable/{applicationId}', function ($applicationId) {
    $applicationController = new ApplicationController();
    $applicationData = $applicationController->show($applicationId)->original;
    $pdf = PDF::loadView('pdf-template', ['application' => $applicationData]);
    $pdfPath = storage_path('app/public/pdf-template.pdf');
    $pdfUrl = asset('storage/pdf-template.pdf');

    $applicationData['pdfPath'] = $pdfPath;
    
    // Create a new Application instance and fill it with the data
    $application = new Application();
    
    $application->pdf_url = $pdfUrl;
    $application->fill($applicationData);

    // dd($applicationData);

    return new ApplicationDataMail($application);
});

Route::get('/pdf-template-preview/{applicationId}', function ($applicationId) {
    // Instantiate the ApplicationController
    $applicationController = new ApplicationController();

    // Call the 'show' method to retrieve the application data
    $show = $applicationController->show($applicationId);

    // Decode the JSON response
    // $application = json_decode($show->original);

    // Check if the application data exists
    if (!$show) {
        abort(404); // Handle the case where application is not found
    }

    // Return the 'pdf-template' view with the application data
    return view('pdf-template', ['application' => $show->original]);
});


Route::get('/pdf-generate', function () {
    $pdf = PDF::loadView('pdf-template');
    return $pdf->download('pdf-template.pdf');
});